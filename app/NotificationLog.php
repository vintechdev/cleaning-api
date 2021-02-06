<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class NotificationLog
 * @package App
 */
class NotificationLog extends Model
{
    const NOTIFICATION_TYPE_BOOKING_CREATED_EMAIL = 'booking_created_email';
    const NOTIFICATION_TYPE_BOOKING_STATUS_CHANGE_EMAIL = 'booking_status_change_email';
}