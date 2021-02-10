<?php

namespace App\Http\Controllers\Backend\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
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
   public function LoadNotifications(Request $request)
   {
       $result = $this->notilogs->LoadNotifications($request);
       return response()->json(['data'=>$result]);
   }
}
