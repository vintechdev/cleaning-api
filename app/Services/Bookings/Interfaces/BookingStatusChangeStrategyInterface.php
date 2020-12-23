<?php

namespace App\Services\Bookings\Interfaces;

use App\Booking;
use App\Exceptions\Booking\BookingStatusChangeException;
use App\Exceptions\Booking\InvalidBookingStatusActionException;
use App\Exceptions\Booking\UnauthorizedAccessException;
use App\User;

/**
 * interface BookingStatusChangeStrategyInterface
 * @package App\Services\Bookings\Interfaces
 */
interface BookingStatusChangeStrategyInterface
{
    /**
     * @param Booking $booking
     * @param User $user
     * @return bool
     * @throws InvalidBookingStatusActionException
     * @throws UnauthorizedAccessException
     * @throws BookingStatusChangeException
     */
    public function changeStatus(Booking $booking, User $user): bool;
}