<?php

namespace App\Events;

use App\Booking;
use App\Events\Interfaces\BookingEvent;
use App\User;
use Illuminate\Queue\SerializesModels;

/**
 * Class BookingCreated
 * @package App\Events
 */
class BookingCreated implements BookingEvent
{
    use SerializesModels;

    /**
     * @var Booking
     */
    private $booking;

    /**
     * @var User
     */
    private $user;

    /**
     * BookingCreated constructor.
     * @param Booking $booking
     * @param User $user
     */
    public function __construct(Booking $booking, User $user)
    {
        $this->booking = $booking;
        $this->user = $user;
    }

    /**
     * @return Booking
     */
    public function getBooking(): Booking
    {
        return $this->booking;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}