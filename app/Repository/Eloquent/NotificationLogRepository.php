<?php


namespace App\Repository\Eloquent;


use App\NotificationLog;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $result =  $this->getUserPushNotificationList($request);

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

    private function getUserPushNotificationList(Request $request): array {
        if (!$fromDate = $request->get('fromDate')) {
            $fromDate = Carbon::now()->subDays('7')
                ->format('Y-m-d 00:00:00');
        }

        return NotificationLog::query()
        ->join('push_notification_logs',
            'notification_logs.id','=','push_notification_logs.notification_log_id')
        ->where('user_id', Auth::user()->id)
        ->where('push_notification_logs.user_type', $request->get('userType', 'user'))
        ->where(function ($query) use ($fromDate) {
            $query->where('push_notification_logs.status', DB::raw("'unread'"));
            $query->orWhere(function ($q) use ($fromDate) {
                $q->where('push_notification_logs.updated_at', '>',
                    DB::raw("'".$fromDate . "'"));
                $q->where('push_notification_logs.status', DB::raw("'read'"));
            });
        })
        ->orderBy('push_notification_logs.status','desc')
        ->orderBy('push_notification_logs.created_at', 'DESC')
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