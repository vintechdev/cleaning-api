<?php

namespace App\Services\Bookings\Interfaces;

use App\Booking;
use App\Exceptions\Booking\BookingStatusChangeException;
use App\Exceptions\Booking\InvalidBookingStatusActionException;
use App\Exceptions\Booking\RecurringBookingStatusChangeException;
use App\Exceptions\Booking\UnauthorizedAccessException;
use App\User;
use Carbon\Carbon;

/**
 * interface BookingStatusChangeStrategyInterface
 * @package App\Services\Bookings\Interfaces
 */
interface BookingStatusChangeStrategyInterface
{
    /**
     * @param Booking $booking
     * @param User $user
     * @param Carbon|null $recurredDate
     * @return Booking | null
     * @throws InvalidBookingStatusActionException
     * @throws UnauthorizedAccessException
     * @throws BookingStatusChangeException
     * @throws RecurringBookingStatusChangeException
     */
    public function changeStatus(Booking $booking, User $user, Carbon $recurredDate = null): ?Booking;

    /**
     * @param string $message
     * @return $this
     */
    public function setStatusChangeMessage(string $message);
}