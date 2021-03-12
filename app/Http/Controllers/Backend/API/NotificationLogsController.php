<?php

namespace App\Http\Controllers\Backend\API;

use App\NotificationLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Hash;
use DB;
use App\Repository\Eloquent\NotificationLogRepository;


class NotificationLogsController extends Controller
{
    /**
     * @var NotificationLogRepository
     */
    private $notilogs;

    public function __construct(NotificationLogRepository $notilogs)
    {
        $this->notilogs = $notilogs;   
    }
    
    public function getNotifications(Request $request)
    {
        $result = $this->notilogs->getUserPushNotifications($request);
        return response()->json(['data'=>$result]);
    }

    public function updatePushNotificationLog(Request $request)
    {
        $request->validate([
            'status' => 'required',
            'userType' => 'required',
            'notificationLogId'=> 'required|integer'
        ], $request->all());

        $result = $this->notilogs->updatePushNotificationByNotificationLog($request->input('notificationLogId'), $request);

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => "Notification Status updated successfully."
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => "Something went wrong."
        ]);
    }
}
