<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NotificationLog
 * @package App
 */
class PushNotificationLogs extends Model
{
    protected $table = 'push_notification_logs';
    use SoftDeletes;
    protected $fillable = ['id', 'notification_log_id', 'title', 'message', 'status'];

    const STATUS_READ = 'read';
    const STATUS_UNREAD = 'unread';

    const PUSH_NOTIFICATION_LOG_USER = [
        'new_booking' => [
            'title' => 'New Booking Created',
            'message' => 'New Booking is created'
        ],
        'booking_status_update' => [
            'title' => 'Booking status changed',
            'message' => 'Booking status has been changed to {status}'
        ],
        'booking_time_update' => [
            'title' => 'Booking time updated',
            'message' => 'Booking has been reschedule on {datetime} by provider.'
        ],
    ];

    const PUSH_NOTIFICATION_LOG_PROVIDER = [
        'new_booking' => [
            'title' => 'New Booking Created',
            'message' => 'New Booking is created'
        ],
        'booking_status_update' => [
            'title' => 'Booking status changed',
            'message' => 'Booking status has been changed to {status}'
        ],
        'booking_time_update' => [
            'title' => 'Booking time updated',
            'message' => 'Booking has been reschedule on {datetime} by provider.'
        ],
    ];

   
    public function notificationlogs()
    {
        return $this->belongsTo(NotificationLogs::class,'notification_log_id','id');
    }
}