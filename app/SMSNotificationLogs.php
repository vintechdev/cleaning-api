<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

/**
 * Class NotificationLog
 * @package App
 */
class SMSNotificationLogs extends Model
{
    protected $table = 'sms_notification_logs';
    use SoftDeletes;
    protected $fillable = ['id', 'message', 'status', 'notification_log_id', 'response'];

    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const STATUS_FAILED = 'failed';

    protected $casts = [
        'response' => 'array'
    ];

    const MESSAGE_LOG_USER = [
        'new_booking' => [
            'message' => 'New cleaning booking request #{booking-id} for {default-service-name} received. We will have update for you soon. Please login to {user-site-name} OR PRO {app-name} APP to view booking details.'
        ],
        'booking_status_update' => [
            'message' => 'Your booking #{booking-id} for {default-service-name} updated. New Status: {booking-status} Please login to {user-site-name} OR PRO {app-name} APP to view booking details and chat with service provider.'
        ],
        'booking_time_update' => [
            'title' => 'Booking time updated',
            'message' => 'Booking has been reschedule on {datetime} by provider.'
        ],
    ];

    const MESSAGE_LOG_PROVIDER = [
        'new_booking' => [
            'message' => 'New cleaning booking request #{booking-id} for {default-service-name} received. Please login to {provider-site-name} OR PRO {app-name} APP to accept this booking asap.'
        ],
        'booking_status_update' => [
            'message' => 'Your booking {booking-id} for {default-service-name} updated. New Status: {booking-status} Please login to {provider-site-name} OR PRO {app-name} APP to view booking and customer details.'
        ],
        'booking_time_update' => [
            'title' => 'Booking time updated',
            'message' => 'Booking has been reschedule on {datetime} by provider.'
        ],
    ];

    public function notificationlogs()
    {
        return $this->belongsTo(NotificationLog::class,'notification_log_id','id');
    }


    public static function getProviderMessage($type)
    {
        return self::MESSAGE_LOG_PROVIDER[$type]['message'];
    }

    public static function getUserMessage($type)
    {
        return self::MESSAGE_LOG_USER[$type]['message'];
    }

    public static function getMessage($type, $role = "user")
    {
        $message = "";

        if ($role == "provider") {
            $message = self::getProviderMessage($type);
        } else  {
            $message = self::getUserMessage($type);
        }

        $message = str_replace('{provider-site-name}', Config::get('const.PROVIDER_URL'), $message);
        $message = str_replace('{user-site-name}', Config::get('const.USER_URL'), $message);
        $message = str_replace('{app-name}', Config::get('const.USER_URL'), $message);

        return $message;
    }

}