<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Bookingrequestprovider;
use App\Bookingstatus;
use App\Exceptions\Booking\BookingStatusChangeException;
use App\Exceptions\Booking\InvalidBookingStatusActionException;
use App\Exceptions\Booking\RecurringBookingStatusChangeException;
use App\Exceptions\Booking\UnauthorizedAccessException;
use App\User;
use Carbon\Carbon;

/**
 * Class CancelBookingStrategy
 * @package App\Services\Bookings
 */
class CancelBookingStrategy extends AbstractBookingStatusChangeStrategy
{
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
        if (!$this->getStatusChangeMessage()) {
            throw new InvalidBookingStatusActionException('Notes are required for cancelling booking');
        }

        if (!$this->canUserCancelBooking($booking, $user)) {
            throw new UnauthorizedAccessException('User does not have permission to cancel this booking');
        }

        if ($booking->isRecurring() && !$recurredDate && $booking->getStatus() != Bookingstatus::BOOKING_STATUS_PENDING) {
            throw new RecurringBookingStatusChangeException('A parent booking can only be cancelled if it is pending');
        }

        if ($booking->isRecurring() && $recurredDate && $booking->getStatus() == Bookingstatus::BOOKING_STATUS_ACCEPTED) {
            $booking = $this
                ->recurringBookingService
                ->findOrCreateRecurringBooking($booking, $recurredDate)
                ->getBooking();
        }

        if ($booking->getStatus() === Bookingstatus::BOOKING_STATUS_CANCELLED) {
            throw new InvalidBookingStatusActionException('Booking is already cancelled');
        }

        if (!in_array($booking->getStatus(), [
            Bookingstatus::BOOKING_STATUS_PENDING,
            Bookingstatus::BOOKING_STATUS_ACCEPTED,
            Bookingstatus::BOOKING_STATUS_ARRIVED
        ])) {
            throw new BookingStatusChangeException('Status of this booking can not be changed to cancelled');
        }

        if (!$booking->setStatus(Bookingstatus::BOOKING_STATUS_CANCELLED)->save()) {
            throw new BookingStatusChangeException('Booking cancellation failed while saving');
        }

        $provider = $this
            ->bookingRequestProviderRepo
            ->getAcceptedBookingRequestProvider($booking);

        if (!$provider->setStatus(Bookingrequestprovider::STATUS_CANCELLED)->save()) {
            throw new BookingStatusChangeException('Booking cancellation failed while saving Booking requests');
        }

        return $booking;
    }

    /**
     * @param Booking $booking
     * @param User $user
     * @return bool
     */
    public function canUserCancelBooking(Booking $booking, User $user): bool
    {
        if (!$this->canUserUpdateBooking($booking, $user)) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if (
            $this->isUserAChosenBookingProvider($user, $booking) &&
            in_array($booking->getStatus(), [Bookingstatus::BOOKING_STATUS_ACCEPTED, Bookingstatus::BOOKING_STATUS_ARRIVED])
        ) {
            $request = $this->bookingRequestProviderRepo->getByBookingAndProviderId($booking->getId(), $user->getId());
            if ($request->getStatus() == Bookingrequestprovider::STATUS_ACCEPTED) {
                return true;
            }
        }

        // If customer and status is pending
        if (
            $this->isUserTheBookingCustomer($user, $booking) &&
            in_array($booking->getStatus(), [Bookingstatus::BOOKING_STATUS_PENDING, Bookingstatus::BOOKING_STATUS_ACCEPTED])
        ) {
            return true;
        }

        return false;
    }
}