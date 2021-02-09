<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Bookingrequestprovider;
use App\Bookingservice;
use App\Bookingstatus;
use App\Exceptions\Booking\BookingStatusChangeException;
use App\Exceptions\Booking\InvalidBookingStatusActionException;
use App\Exceptions\Booking\RecurringBookingStatusChangeException;
use App\Exceptions\Booking\UnauthorizedAccessException;
use App\Repository\BookingReqestProviderRepository;
use App\Services\BookingFinalCostCalculator;
use App\Services\Bookings\Exceptions\BookingserviceBuilderException;
use App\Services\Bookings\Exceptions\BookingServicesManagerException;
use App\Services\Bookings\Traits\BookingPaymentHandlerTrait;
use App\Services\Payments\Interfaces\PaymentProcessorInterface;
use App\Services\RecurringBookingService;
use App\User;
use Carbon\Carbon;

/**
 * Class CompleteBookingStrategy
 * @package App\Services\Bookings
 */
class CompleteBookingStrategy extends AbstractBookingStatusChangeStrategy
{
    use BookingPaymentHandlerTrait;

    /**
     * @var array
     */
    private $services = [];

    /**
     * @var BookingServicesManager
     */
    private $bookingServicesManager;

    /**
     * @var PaymentProcessorInterface
     */
    private $paymentProcessor;

    /**
     * @var BookingFinalCostCalculator
     */
    private $finalCostCalculator;

    /**
     * CompleteBookingStrategy constructor.
     * @param BookingReqestProviderRepository $bookingRequestProviderRepository
     * @param BookingVerificationService $bookingVerificationService
     * @param RecurringBookingService $recurringBookingService
     * @param BookingServicesManager $bookingServicesManager
     * @param PaymentProcessorInterface $paymentProcessor
     * @param BookingFinalCostCalculator $finalCostCalculator
     */
    public function __construct(
        BookingReqestProviderRepository $bookingRequestProviderRepository,
        BookingVerificationService $bookingVerificationService,
        RecurringBookingService $recurringBookingService,
        BookingServicesManager $bookingServicesManager,
        PaymentProcessorInterface $paymentProcessor,
        BookingFinalCostCalculator $finalCostCalculator
    ) {
        parent::__construct($bookingRequestProviderRepository, $bookingVerificationService, $recurringBookingService);
        $this->bookingServicesManager = $bookingServicesManager;
        $this->paymentProcessor = $paymentProcessor;
        $this->finalCostCalculator = $finalCostCalculator;
    }

    /**
     * @param Booking $booking
     * @param User $user
     * @param Carbon|null $recurredDate
     * @return Booking
     * @throws RecurringBookingStatusChangeException
     * @throws UnauthorizedAccessException
     */
    protected function handleStatusChange(Booking $booking, User $user, Carbon $recurredDate = null): Booking
    {
        if ($booking->isRecurring() && !$recurredDate) {
            throw new RecurringBookingStatusChangeException(
                'Status for recurring booking cannot be changed to completed. Individual recurred booking items need to be changed.'
            );
        }

        if (!$this->canUserCompleteBooking($booking, $user)) {
            throw new UnauthorizedAccessException('User does not have access to this function');
        }

        $bookingRequestProvider = $this
            ->bookingRequestProviderRepo
            ->getByBookingAndProviderId($booking->getId(), $user->getId());

        $provider = ($user->getId() === $bookingRequestProvider->provider_user_id) ?
            $user :
            User::find($bookingRequestProvider->provider_user_id);

        $this
            ->updateBookingServices(
                $booking,
                $provider
            );

        if (!$this->finalCostCalculator->updateBookingGrandTotal($booking)) {
            throw new BookingStatusChangeException('Unable to update final total for booking');
        }

        if ($this->hasBookingDetailsChanged($booking)) {
            if (!$booking->setStatus(Bookingstatus::BOOKING_STATUS_PENDING_APPROVAL)->save()) {
                throw new BookingStatusChangeException('Unable to save booking status');
            }

            return $booking;
        }

        if (!$booking->setStatus(Bookingstatus::BOOKING_STATUS_COMPLETED)->save()) {
            throw new BookingStatusChangeException('Unable to save booking status');
        }

        $this->handlePayment($booking, $provider);
        return $booking;
    }

    /**
     * @param Booking $booking
     * @param User $user
     * @return bool
     * @throws InvalidBookingStatusActionException
     */
    public function canUserCompleteBooking(Booking $booking, User $user): bool
    {
        if ($booking->getStatus() != Bookingstatus::BOOKING_STATUS_ARRIVED) {
            throw new InvalidBookingStatusActionException('Can not change the status of this booking to complete');
        }

        if (!$this->canUserUpdateBooking($booking, $user)) {
            return false;
        }

        if ($this->isUserAChosenBookingProvider($user, $booking)) {
            $bookingRequestProvider = $this
                ->bookingRequestProviderRepo
                ->getByBookingAndProviderId($booking->getId(), $user->getId());
            if ($bookingRequestProvider->getStatus() == Bookingrequestprovider::STATUS_ACCEPTED) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $services
     * @return CompleteBookingStrategy
     */
    public function setServicesDetails(array $services): CompleteBookingStrategy
    {
        $this->services = $services;
        return $this;
    }

    /**
     * @param Booking $booking
     * @return bool
     */
    private function hasBookingDetailsChanged(Booking $booking): bool
    {
        if (!$this->services) {
            throw new InvalidBookingStatusActionException('Can not change status for booking without providing any services details');
        }

        if (count($this->services) !== $booking->getBookingServices()->count()) {
            return true;
        }

        $actualServices = [];

        /** @var Bookingservice $bookingService */
        foreach ($booking->getBookingServices() as $bookingService) {
            $actualServices[$bookingService->getService()->getId()] = $bookingService;
        }

        foreach ($this->services as $bookingService) {
            if (!in_array($bookingService['service_id'], array_keys($actualServices))) {
                return true;
            }

            $actualService = $actualServices[$bookingService['service_id']];
            if ($actualService->getInitialNumberOfHours() !== $actualService['final_number_of_hours']) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Booking $booking
     * @return bool
     */
    private function updateBookingServices(Booking $booking, User $user): bool
    {
        if (!$this->services) {
            throw new InvalidBookingStatusActionException('Can not change status for booking without providing any services details');
        }

        foreach ($this->services as &$service) {
            $service['provider_id'] = $user->getId();
        }

        try {
            $this->bookingServicesManager->updateBookingServicesFromArray($booking, $this->services);
        } catch (BookingserviceBuilderException $exception) {
            throw new InvalidBookingStatusActionException($exception->getMessage());
        } catch (BookingServicesManagerException $exception) {
            throw new InvalidBookingStatusActionException($exception->getMessage());
        }

        return true;
    }

    /**
     * @return PaymentProcessorInterface
     */
    protected function getPaymentProcessor(): PaymentProcessorInterface
    {
        return $this->paymentProcessor;
    }
}