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
use App\UserNotification;


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
        $user_id = Auth::user()->id;
        $Notification = Notification::with(array('usernotification'=>function($query) use ($user_id){
            $query->where('user_id',$user_id);
        }))->get();
		
        return response()->json( $Notification, 200);
       // return (new NotificationCollection($Notification));
    }

    //for update notifications by uuid
    public function editnotifications(Request $request)
    {
 
        $validator = Validator::make($request->all(), [
            'id'=>'required|array',
            'allow_sms' => 'nullable|array',
            'allow_email' => 'nullable|array',
            'allow_push' => 'nullable|array',
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }
       
        $id = $request->id;
        $allow_sms = $request->allow_sms;
        $allow_email = $request->allow_email;
        $allow_push = $request->allow_push;
        if(count($id)>0){
           
            foreach($id as $k=>$v){
                $Notification = UserNotification::firstOrNew(['notification_id' => $v,'user_id'=>Auth::user()->id]);
                $Notification->notification_id = $v;
                $Notification->user_id =Auth::user()->id;
                $Notification->sms = ($allow_sms!= null && array_key_exists($v,$allow_sms )?$allow_sms[$v]:0);
                $Notification->email = ($allow_email!= null && array_key_exists($v,$allow_email )?$allow_email[$v]:0);
                $Notification->push = ($allow_push!= null && array_key_exists($v,$allow_push )?$allow_push[$v]:0);
                $Notification->save();
            }

        }
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
