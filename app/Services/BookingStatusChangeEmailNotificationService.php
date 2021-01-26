<?php

namespace App\Services;

use App\NotificationLog;

/**
 * Class BookingStatusChangeEmailNotificationService
 * @package App\Services
 */
class BookingStatusChangeEmailNotificationService extends AbstractBookingNotificationService
{
    protected function sendNotification(): bool
    {
        // TODO: Implement sendNotification() method.
    }

    /**
     * @return string
     */
    protected function getNotificationType(): string
    {
        return NotificationLog::NOTIFICATION_TYPE_BOOKING_STATUS_CHANGE_EMAIL;
    }
}