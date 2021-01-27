<?php

namespace App\Services;

use App\NotificationLog;

/**
 * Class BookingStatusChangeEmailNotificationService
 * @package App\Services
 */
class BookingStatusChangeEmailNotificationService extends AbstractBookingNotificationService
{
    protected $booking;
    public function __construct(){
        
    }
    

    protected function sendNotification(): bool
    {
        echo "in";exit;
        dd($this->booking);
        // TODO: Implement sendNotification() method.
       // return false;
    }

    /**
     * @return string
     */
    protected function getNotificationType(): string
    {
        return NotificationLog::NOTIFICATION_TYPE_BOOKING_STATUS_CHANGE_EMAIL;
    }
}