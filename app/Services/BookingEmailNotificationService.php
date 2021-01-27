<?php


namespace App\Services;

use App\NotificationLog;
use App\Booking;
/**
 * Class BookingEmailNotificationService
 * @package App\Services
 */
class BookingEmailNotificationService extends AbstractBookingNotificationService
{
 public function __construct(Booking $booking)
 {
    
     
 }
    protected function sendNotification(): bool
    {
        //TODO: Check if the user have opted to send email. If not return false. If yes, add logic to send email here and return true.



      //  dd($booking->id);

        return false;
    }

    protected function getNotificationType(): string
    {
        return NotificationLog::NOTIFICATION_TYPE_BOOKING_CREATED_EMAIL;
    }
}