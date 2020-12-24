<?php

namespace App\Events\Interfaces;

use App\Booking;
use App\User;

/**
 * Interface BookingEvent
 * @package App\Events\Interfaces
 */
interface BookingEvent
{
    /**
     * @return Booking
     */
    public function getBooking(): Booking;

    /**
     * @return User
     */
    public function getUser(): User;
}