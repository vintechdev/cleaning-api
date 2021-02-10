<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NotificationLog
 * @package App
 */
class EmailNotificationLogs extends Model
{
    protected $table = 'email_notification_logs';
    use SoftDeletes;
    protected $fillable = ['id'];
   
    public function notificationlogs()
    {
        return $this->belongsTo(NotificationLogs::class,'notification_log_id','id');
    }
}