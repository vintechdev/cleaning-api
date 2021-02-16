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

    public function getUserPushNotifications(Request $request)
    {
        if ($request->get('type') == 'count') {
            return $this->countUserNotification($request);
        }

        $result =  $this->getUserPushNotificationList($request, "unread");

        $this->updateNotificationStatus($request , "read");

        return $result;
    }

    private function countUserNotification(Request $request): int {
        $count = NotificationLog::query()
            ->join('push_notification_logs',
                'notification_logs.id','=','push_notification_logs.notification_log_id')
            ->where('push_notification_logs.status','unread')
            ->where('notification_logs.user_id', Auth::user()->id)
            ->where('push_notification_logs.user_type', $request->get('userType', 'user'))
            ->count();

        return intval($count);
    }

    private function getUserPushNotificationList(Request $request, string $status): array {
        return NotificationLog::query()
        ->join('push_notification_logs',
            'notification_logs.id','=','push_notification_logs.notification_log_id')
        ->where('user_id', Auth::user()->id)
        ->orderBy('push_notification_logs.status','desc')
        ->orderBy('push_notification_logs.created_at', 'DESC')
        ->where('push_notification_logs.status', $status)
        ->where('push_notification_logs.user_type', $request->get('userType', 'user'))
        ->get()
        ->toArray();
    }

    private function updateNotificationStatus(Request $request, string $status) {
        // TODO: Date can be added
        return NotificationLog::query()
            ->join('push_notification_logs',
                'notification_logs.id','=','push_notification_logs.notification_log_id')
            ->where('user_id', Auth::user()->id)
            ->where('status','unread')
            ->where('push_notification_logs.user_type', $request->get('userType', 'user'))
            ->update(['status'=> $status]);
    }

}