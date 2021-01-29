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
use App\PaymentDto;
use App\Providermetadatum;
use App\Repository\BookingReqestProviderRepository;
use App\Services\Bookings\Exceptions\BookingserviceBuilderException;
use App\Services\Bookings\Exceptions\BookingServicesManagerException;
use App\Services\Payments\Exceptions\CreditCardNotSetUpException;
use App\Services\Payments\Exceptions\PaymentAccountNotSetUpException;
use App\Services\Payments\Exceptions\PaymentFailedException;
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
    //TODO make this configurable
    const SERVICE_FEE_PERCENT = '1';

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
     * CompleteBookingStrategy constructor.
     * @param BookingReqestProviderRepository $bookingRequestProviderRepository
     * @param BookingVerificationService $bookingVerificationService
     * @param RecurringBookingService $recurringBookingService
     * @param BookingServicesManager $bookingServicesManager
     * @param PaymentProcessorInterface $paymentProcessor
     */
    public function __construct(
        BookingReqestProviderRepository $bookingRequestProviderRepository,
        BookingVerificationService $bookingVerificationService,
        RecurringBookingService $recurringBookingService,
        BookingServicesManager $bookingServicesManager,
        PaymentProcessorInterface $paymentProcessor
    ) {
        parent::__construct($bookingRequestProviderRepository, $bookingVerificationService, $recurringBookingService);
        $this->bookingServicesManager = $bookingServicesManager;
        $this->paymentProcessor = $paymentProcessor;
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

        if (!$booking->setStatus(Bookingstatus::BOOKING_STATUS_COMPLETED)->save()) {
            throw new BookingStatusChangeException('Unable to save booking status');
        }

        try {
            $paymentSuccess = $this->handlePayment($booking, $provider);
        } catch (PaymentAccountNotSetUpException $exception) {
            throw new PaymentFailedException('Payment failed as account details are not set up');
        } catch (CreditCardNotSetUpException $exception) {
            throw new PaymentFailedException('Customer\'s credit card is not set up or is expired');
        }

        if (!$paymentSuccess) {
            throw new PaymentFailedException('Payment failed for some reason. Please contact administrator.');
        }

        return $booking;
    }

    /**
     * @param Booking $booking
     * @param User $provider
     * @return bool
     * @throws CreditCardNotSetUpException
     * @throws PaymentAccountNotSetUpException
     * @throws \App\Services\Payments\Exceptions\InvalidPaymentDataException
     * @throws \App\Services\Payments\Exceptions\InvalidUserException
     */
    private function handlePayment(Booking $booking, User $provider): bool
    {
        $paymentDto = new PaymentDto();
        $providerMetadata = Providermetadatum::findByProviderId($provider->getId());
        $serviceFeePercentage = !is_null($providerMetadata) ?
            $providerMetadata->getServiceFeePercentage() :
            self::SERVICE_FEE_PERCENT;

        $paymentDto
            ->setAmount($booking->getFinalCost())
            ->setMetadata(['booking_id' => $booking->getId()])
            ->setPayer(User::find($booking->getUserId()))
            ->setPayee($provider)
            ->setTransferFeePercentage($serviceFeePercentage)
            // TODO: Update payment description
            ->setPaymentDescription('Booking id:' . $booking->getId());

        return $this
            ->paymentProcessor
            ->setPaymentData($paymentDto)
            ->process();
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
}