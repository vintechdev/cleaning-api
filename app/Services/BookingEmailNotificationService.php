<?php


namespace App\Services;

use App\NotificationLog;

/**
 * Class BookingEmailNotificationService
 * @package App\Services
 */
class BookingEmailNotificationService extends AbstractBookingNotificationService
{

    protected function sendNotification(): bool
    {
        //TODO: Check if the user have opted to send email. If not return false. If yes, add logic to send email here and return true.
        return false;
    }

    protected function getNotificationType(): string
    {
        return NotificationLog::NOTIFICATION_TYPE_BOOKING_CREATED_EMAIL;
    }
}