<?php

namespace App\Services;

use App\Booking;
use App\Events\Interfaces\BookingEvent;
use App\Repository\Eloquent\NotificationLogRepository;
use App\Services\Interfaces\BookingNotificationInterface;
use App\Bookingstatus;
use App\Events\BookingStatusChanged;

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
        return true;
       // return false;
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
    public function getStatus(BookingEvent $event){

       // $this->old_status = Bookingstatus::getStatusNameById($event->getOldStatus());
       // $this->new_status = Bookingstatus::getStatusNameById($event->getNewStatus());
        return $this;

    }
}