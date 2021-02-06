<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Bookingstatus;
use App\Exceptions\Booking\BookingStatusChangeException;
use App\Exceptions\Booking\InvalidBookingStatusActionException;
use App\Exceptions\Booking\RecurringBookingStatusChangeException;
use App\Exceptions\Booking\UnauthorizedAccessException;
use App\Repository\BookingReqestProviderRepository;
use App\Services\Bookings\Traits\BookingPaymentHandlerTrait;
use App\Services\Payments\Exceptions\PaymentFailedException;
use App\Services\Payments\Interfaces\PaymentProcessorInterface;
use App\Services\RecurringBookingService;
use App\User;
use Carbon\Carbon;

/**
 * Class ApproveBookingStrategy
 * @package App\Services\Bookings
 */
class ApproveBookingStrategy extends AbstractBookingStatusChangeStrategy
{
    use BookingPaymentHandlerTrait;

    /**
     * @var PaymentProcessorInterface
     */
    private $paymentProcessor;

    /**
     * ApproveBookingStrategy constructor.
     * @param BookingReqestProviderRepository $bookingRequestProviderRepository
     * @param BookingVerificationService $bookingVerificationService
     * @param RecurringBookingService $recurringBookingService
     * @param PaymentProcessorInterface $paymentProcessor
     */
    public function __construct(
        BookingReqestProviderRepository $bookingRequestProviderRepository,
        BookingVerificationService $bookingVerificationService,
        RecurringBookingService $recurringBookingService,
        PaymentProcessorInterface $paymentProcessor
    ) {
        parent::__construct($bookingRequestProviderRepository, $bookingVerificationService, $recurringBookingService);
        $this->paymentProcessor = $paymentProcessor;
    }

    /**
     * @param Booking $booking
     * @param User $user
     * @param Carbon|null $recurredDate
     * @return Booking
     * @throws InvalidBookingStatusActionException
     * @throws UnauthorizedAccessException
     * @throws BookingStatusChangeException
     * @throws RecurringBookingStatusChangeException
     * @throws PaymentFailedException
     */
    protected function handleStatusChange(Booking $booking, User $user, Carbon $recurredDate = null): Booking
    {
        if ($booking->isRecurring() && !$recurredDate) {
            throw new RecurringBookingStatusChangeException(
                'Status for recurring booking cannot be changed to completed. Individual recurred booking items need to be changed.'
            );
        }

        if (!$this->canUserApproveBooking($booking, $user)) {
            throw new UnauthorizedAccessException('User does not have access to this function');
        }

        if (!$booking->setStatus(Bookingstatus::BOOKING_STATUS_COMPLETED)->save()) {
            throw new BookingStatusChangeException('Unable to save booking status');
        }

        $requestProvider = $this
            ->bookingRequestProviderRepo
            ->getAcceptedBookingRequestProvider($booking);

        $this->handlePayment($booking, User::find($requestProvider->getProviderId()));

        return $booking;
    }

    /**
     * @param Booking $booking
     * @param User $user
     * @return bool
     */
    public function canUserApproveBooking(Booking $booking, User $user): bool
    {
        if ($booking->getStatus() != Bookingstatus::BOOKING_STATUS_PENDING_APPROVAL) {
            throw new InvalidBookingStatusActionException('Can not change the status of this booking to approved');
        }

        if (!$this->canUserUpdateBooking($booking, $user)) {
            return false;
        }

        if (!$this->isUserTheBookingCustomer($user, $booking)) {
            return false;
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