<?php

namespace App\Events;

use App\Booking;
use Illuminate\Queue\SerializesModels;

/**
 * Class BookingCreated
 * @package App\Events
 */
class BookingCreated
{
    use SerializesModels;

    /**
     * @var Booking
     */
    private $booking;

    /**
     * BookingCreated constructor.
     * @param Booking $booking
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * @return Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }
}