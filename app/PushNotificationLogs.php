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
    protected $fillable = ['id'];
   
    public function notificationlogs()
    {
        return $this->belongsTo(NotificationLogs::class,'notification_log_id','id');
    }
}