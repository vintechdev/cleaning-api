<?php

namespace App\Services;

use App\Booking;
use App\Services\Interfaces\BookingNotificationInterface;

/**
 * Class CompositeBookingNotificationService
 * @package App\Services
 */
class CompositeBookingNotificationService implements BookingNotificationInterface
{
    /**
     * @var BookingNotificationInterface[]
     */
    private $notificationService;

    /**
     * CompositeBookingNotificationService constructor.
     */
    public function __construct()
    {
        $this->notificationService = [];
    }

    /**
     * @param BookingNotificationInterface $notificationService
     * @return $this
     */
    public function add(BookingNotificationInterface $notificationService)
    {
        $this->notificationService[] = $notificationService;
        return $this;
    }

    /**
     * @return boolean
     */
    public function send(): bool
    {
        foreach ($this->notificationService as $notificationService) {
            $notificationService->send();
        }

        return true;
    }

    /**
     * @param Booking $booking
     * @return $this
     */
    public function setBooking(Booking $booking)
    {
        foreach ($this->notificationService as $notificationService) {
            $notificationService->setBooking($booking);
        }

        return $this;
    }
}