<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Bookingrequestprovider;
use App\Bookingstatus;
use App\Events\BookingStatusChanged;
use App\Exceptions\Booking\BookingStatusChangeException;
use App\Exceptions\Booking\InvalidBookingStatusActionException;
use App\Exceptions\Booking\RecurringBookingStatusChangeException;
use App\Exceptions\Booking\UnauthorizedAccessException;
use App\Repository\BookingReqestProviderRepository;
use App\Services\Payments\Exceptions\PaymentFailedException;
use App\Services\Payments\Interfaces\PaymentProcessorInterface;
use App\Services\Payments\Interfaces\PaymentUserValidatorInterface;
use App\Services\RecurringBookingService;
use App\User;
use Carbon\Carbon;

/**
 * Class ArriveBookingStrategy
 * @package App\Services\Bookings
 */
class ArriveBookingStrategy extends AbstractBookingStatusChangeStrategy
{
    /**
     * @var PaymentUserValidatorInterface
     */
    private $paymentUserValidator;

    /**
     * ArriveBookingStrategy constructor.
     * @param BookingReqestProviderRepository $bookingRequestProviderRepository
     * @param BookingVerificationService $bookingVerificationService
     * @param RecurringBookingService $recurringBookingService
     * @param PaymentUserValidatorInterface $paymentUserValidator
     */
    public function __construct(
        BookingReqestProviderRepository $bookingRequestProviderRepository,
        BookingVerificationService $bookingVerificationService,
        RecurringBookingService $recurringBookingService,
        PaymentUserValidatorInterface $paymentUserValidator
    ) {
        parent::__construct($bookingRequestProviderRepository, $bookingVerificationService, $recurringBookingService);
        $this->paymentUserValidator = $paymentUserValidator;
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
     */
    protected function handleStatusChange(Booking $booking, User $user, Carbon $recurredDate = null): Booking
    {
        if ($booking->isRecurring()) {
            if (!$recurredDate) {
                throw new RecurringBookingStatusChangeException(
                    'Status for recurring booking cannot be changed to arrived. Individual recurred booking items need to be changed.'
                );
            }

            $booking = $this->recurringBookingService
                ->findOrCreateRecurringBooking($booking, $recurredDate)
                ->getBooking();
        }

        if (!$this->canUserArriveForBooking($booking, $user)) {
            throw new UnauthorizedAccessException('User does not have access to this function');
        }

        if (!$this->paymentUserValidator->isUsersCardValid(User::find($booking->getUserId()))) {
            throw new PaymentFailedException('Customer does not have a valid credit card set up.');
        }

        if (!$booking->setStatus(Bookingstatus::BOOKING_STATUS_ARRIVED)->save()) {
            throw new BookingStatusChangeException('Failed to change the status');
        }

        return $booking;
    }

    /**
     * @param Booking $booking
     * @param User $user
     * @return bool
     * @throws InvalidBookingStatusActionException
     */
    public function canUserArriveForBooking(Booking $booking, User $user): bool
    {
        if ($booking->getStatus() != Bookingstatus::BOOKING_STATUS_ACCEPTED) {
            throw new InvalidBookingStatusActionException('Can not change the status of this booking to arrived');
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
}
