<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Events\BookingStatusChanged;
use App\Exceptions\Booking\BookingStatusChangeException;
use App\Exceptions\Booking\InvalidBookingStatusActionException;
use App\Exceptions\Booking\RecurringBookingStatusChangeException;
use App\Exceptions\Booking\UnauthorizedAccessException;
use App\Services\Bookings\Interfaces\BookingStatusChangeStrategyInterface;
use App\User;

/**
 * Class BookingStatusChangeContext
 * @package App\Services\Bookings
 */
class BookingStatusChangeContext
{
    /**
     * @var BookingStatusChangeStrategyInterface
     */
    private $bookingStatusChangeStrategy;

    /**
     * BookingStatusChangeContext constructor.
     * @param BookingStatusChangeStrategyInterface $bookingStatusChangeStrategy
     */
    public function __construct(BookingStatusChangeStrategyInterface $bookingStatusChangeStrategy)
    {
        $this->bookingStatusChangeStrategy = $bookingStatusChangeStrategy;
    }

    /**
     * @param Booking $booking
     * @param User $user
     * @return bool
     * @throws InvalidBookingStatusActionException
     * @throws UnauthorizedAccessException
     * @throws BookingStatusChangeException
     * @throws RecurringBookingStatusChangeException
     */
    public function changeStatus(Booking $booking, User $user): bool
    {
        $oldStatus = $booking->getStatus();
        if ($this->bookingStatusChangeStrategy->changeStatus($booking, $user)) {
            event(new BookingStatusChanged($booking, $user, $oldStatus, $booking->getStatus()));
            return true;
        }

        return false;
    }
}
