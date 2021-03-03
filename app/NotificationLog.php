<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NotificationLog
 * @package App
 */
class NotificationLog extends Model
{
    protected $table = 'notification_logs';
    use SoftDeletes;
    protected $fillable = ['id'];
    const NOTIFICATION_TYPE_BOOKING_CREATED_EMAIL = 'booking_created_email';
    const NOTIFICATION_TYPE_BOOKING_STATUS_CHANGE_EMAIL = 'booking_status_change_email';
    const NOTIFICATION_TYPE_BOOKING_CREATED_PUSH = 'booking_created_push_notification';
    const NOTIFICATION_TYPE_BOOKING_STATUS_CHANGE_PUSH = 'booking_status_change_notification';

    const NOTIFICATION_LOG_USER_TYPE_USER = 'user';
    const NOTIFICATION_LOG_USER_TYPE_PROVIDER = 'provider';

    public function emails()
    {
        return $this->hasMany(EmailNotificationLogs::class,'notification_log_id','id');
    }
    public function sms()
    {
        return $this->hasMany(SMSNotificationLogs::class,'notification_log_id','id');
    }
    public function push()
    {
        return $this->hasMany(PushNotificationLogs::class,'notification_log_id','id');
    }
}