<?php

namespace App\Http\Controllers\Backend\API;

use App\Notification;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\NotificationRequest;
use App\Http\Resources\NotificationCollection;
use App\Http\Resources\Notification as NotificationResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use Hash;
use DB;


class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $notifications = Notification::query();
        
		if ($request->has('id')) {
			$notifications = $notifications->where('id', 'LIKE', '%'.$request->get('id').'%');
		}
		if ($request->has('notification_type')) {
			$notifications = $notifications->where('notification_type', 'LIKE', '%'.$request->get('notification_type').'%');
		}
		if ($request->has('notification_name')) {
			$notifications = $notifications->where('notification_name', 'LIKE', '%'.$request->get('notification_name').'%');
		}
		if ($request->has('notification_description')) {
			$notifications = $notifications->where('notification_description', 'LIKE', '%'.$request->get('notification_description').'%');
		}
		if ($request->has('allow_sms')) {
			$notifications = $notifications->where('allow_sms', 'LIKE', '%'.$request->get('allow_sms').'%');
		}
		if ($request->has('allow_email')) {
			$notifications = $notifications->where('allow_email', 'LIKE', '%'.$request->get('allow_email').'%');
		}
		if ($request->has('allow_push')) {
			$notifications = $notifications->where('allow_push', 'LIKE', '%'.$request->get('allow_push').'%');
		}
        $notifications = $notifications->paginate(20);
        return (new NotificationCollection($notifications));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(NotificationRequest $request, Notification $notification)
    {
        $notification = Notification::firstOrNew(['id' => $request->get('id')]);
        $notification->id = $request->get('id');
		$notification->notification_uuid = $request->get('notification_uuid');
		$notification->notification_type = $request->get('notification_type');
		$notification->notification_name = $request->get('notification_name');
		$notification->notification_description = $request->get('notification_description');
		$notification->allow_sms = $request->get('allow_sms');
		$notification->allow_email = $request->get('allow_email');
		$notification->allow_push = $request->get('allow_push');
        $notification->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }

    //for get notifications
    public function getnotifications(Request $request)
    {
        $Notification = Notification::query();
		
        $Notification = $Notification->paginate(20);
        return (new NotificationCollection($Notification));
    }

    //for update notifications by uuid
    public function editnotifications(Request $request, $uuid)
    {
 
        $validator = Validator::make($request->all(), [
            'allow_sms' => 'required',
            'allow_email' => 'required',
            'allow_push' => 'required'
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }
       

        $Notification = Notification::firstOrNew(['uuid' => $uuid]);
        $Notification->allow_sms = $request->get('allow_sms');
        $Notification->allow_email = $request->get('allow_email');
        $Notification->allow_push = $request->get('allow_push');
        $Notification->save();
        
        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => $Notification], $responseCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $notification = Notification::find($request->get('id'));
        $notification->delete();
        return response()->json(['no_content' => true], 200);
    }

    /**
     * Restore the specified resource to storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        $notification = Notification::withTrashed()->find($request->get('id'));
        $notification->restore();
        return response()->json(['no_content' => true], 200);
    }
}
