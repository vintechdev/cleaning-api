<?php

namespace App\Events;

use App\Booking;
use App\Events\Interfaces\BookingEvent;
use App\User;
use Illuminate\Queue\SerializesModels;

/**
 * Class BookingStatusChanged
 * @package App\Events
 */
class BookingStatusChanged implements BookingEvent
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
     * @var int
     */
    private $oldStatus;

    /**
     * @var int
     */
    private $newStatus;

    /**
     * BookingStatusChanged constructor.
     * @param Booking $booking
     * @param User $user
     */
    public function __construct(Booking $booking, User $user, int $oldStatus, int $newStatus)
    {
        $this->booking = $booking;
        $this->user = $user;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
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

    /**
     * @return int
     */
    public function getOldStatus(): int
    {
        return $this->oldStatus;
    }

    /**
     * @return int
     */
    public function getNewStatus(): int
    {
        return $this->newStatus;
    }
}