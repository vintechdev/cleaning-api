<?php

namespace App\Services\Interfaces;

use App\Booking;

/**
 * Interface BookingNotificationInterface
 * @package App\Services\Interfaces
 */
interface BookingNotificationInterface extends NotificationInterface
{
    /**
     * @param Booking $booking
     * @return $this
     */
    public function setBooking(Booking $booking);
}