<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function notificationlogs()
    {
        return $this->belongsTo(NotificationLog::class,'notification_log_id','id');
    }
}