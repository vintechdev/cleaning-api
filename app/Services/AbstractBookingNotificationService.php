<?php

namespace App\Services;

use App\Booking;
use App\Repository\Eloquent\NotificationLogRepository;
use App\Services\Interfaces\BookingNotificationInterface;

/**
 * Class AbstractBookingNotificationService
 * @package App\Services
 */
abstract class AbstractBookingNotificationService implements BookingNotificationInterface
{
    /**
     * @var NotificationLogRepository
     */
    protected $notificationLogRepo;

    /**
     * @var Booking
     */
    protected $booking;

    /**
     * AbstractBookingNotificationService constructor.
     * @param NotificationLogRepository $notificationLogRepository
     */
    public function __construct(
        NotificationLogRepository $notificationLogRepository
    ) {
        $this->notificationLogRepo = $notificationLogRepository;
    }

    /**
     * @return bool
     */
    abstract protected function sendNotification(): bool;

    /**
     * @return string
     */
    abstract protected function getNotificationType(): string;

    /**
     * @return bool
     */
    public function send(): bool
    {
        if ($this->sendNotification()) {
            // Log the notification using notification log repo.
            
        }
        return false;
    }

    /**
     * @param Booking $booking
     * @return $this
     */
    public function setBooking(Booking $booking)
    {
        $this->booking = $booking;
        return $this;
    }
}