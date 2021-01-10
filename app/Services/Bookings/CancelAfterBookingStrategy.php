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
 * Class CancelAfterBookingStrategy
 * @package App\Services\Bookings
 */
class CancelAfterBookingStrategy extends CancelBookingStrategy
{
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
        if (!$this->getStatusChangeMessage()) {
            throw new InvalidBookingStatusActionException('Notes are required for cancelling bookings');
        }

        if (!$booking->isRecurring() && !$booking->isChildBooking()) {
            throw new BookingStatusChangeException('This action is not permitted for this booking');
        }

        if (in_array(
            $booking->getStatus(),
            [
                Bookingstatus::BOOKING_STATUS_PENDING,
                Bookingstatus::BOOKING_STATUS_REJECTED
            ]
        )) {
            throw new BookingStatusChangeException('This action is not permitted for this booking');
        }

        if ($booking->isChildBooking()) {
            $date = $booking->getStartDate();
            $booking = $booking->getParentBooking();
        } else {
            if (!$recurredDate) {
                throw new InvalidBookingStatusActionException('Recurring date has to be passed along with parent to cancel bookings after the date.');
            }

            if (!$this->recurringBookingService->isValidRecurringDate($booking, $recurredDate)) {
                throw new \InvalidArgumentException('Date passed is not a valid recurring date.');
            }

            $date = $recurredDate;
        }

        if (!$this->canUserCancelBooking($booking, $user)) {
            throw new UnauthorizedAccessException('User is not allowed to perform this action');
        }

        if (!$this->recurringBookingService->cancelAllBookingsAfter($booking, $date)) {
            throw new BookingStatusChangeException('Failed to cancel the bookings');
        }

        return $booking;
    }
}