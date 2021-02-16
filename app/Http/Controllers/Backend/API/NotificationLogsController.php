<?php

namespace App\Http\Controllers\Backend\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Hash;
use DB;
use App\Repository\Eloquent\NotificationLogRepository;


class NotificationLogsController extends Controller
{
    public function __construct(NotificationLogRepository $notilogs)
    {
        $this->notilogs = $notilogs;   
    }
   public function getNotifications(Request $request)
   {
       $result = $this->notilogs->getUserPushNotifications($request);
       return response()->json(['data'=>$result]);
   }
}
