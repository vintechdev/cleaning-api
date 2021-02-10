<?php


namespace App\Repository\Eloquent;


use App\NotificationLog;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationLogRepository extends AbstractBaseRepository
{
    protected function getModelClass(): string
    {
        return NotificationLog::class;
    }

    public  function LoadNotifications(Request $request)
    {
        $type = $request->type;
        if($type=='count'){
            return NotificationLog::join('push_notification_logs','notification_logs.id','=','push_notification_logs.notification_log_id')->where('push_notification_logs.status','unread')->count();
        }else{

            $result =  NotificationLog::join('push_notification_logs','notification_logs.id','=','push_notification_logs.notification_log_id')->where('user_id',Auth::user()->id)->orderBy('status','desc')->get()->toArray();

            NotificationLog::join('push_notification_logs','notification_logs.id','=','push_notification_logs.notification_log_id')->where('user_id',Auth::user()->id)->where('status','unread')->update(['status'=>'read']);

            return $result;
        }
    }

}