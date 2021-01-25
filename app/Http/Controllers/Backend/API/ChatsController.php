<?php

namespace App\Http\Controllers\Backend\API;

use App\Chats;
use App\Http\Resources\Bookingservice as ResourcesBookingservice;
use App\User;
use Carbon\Carbon;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Repository\Eloquent\ChatsRepository;
use App\Services\Bookings\BookingService;

/**
 * Class BookingJobsController
 * @package App\Http\Controllers\Backend\API
 */
class ChatsController extends Controller
{

public function list(Request $request,BookingService $bookingService)
{
    $data = $bookingService->getBookingsForChat();
    return response()->json(['success'=>true,'data'=>$data],200);
    
}

   public function addmessage(Request $request,$bookingid)
   {
      
    $validator = Validator::make($request->all(), [
        'receiver_id' => 'required|numeric',
        'message'=>'required|string'
    ]);

    if($validator->fails()){
        $message = $validator->messages()->all();
        return response()->json(['message' => $message], 401);
    }

    $res = app(ChatsRepository::class)->addmessage($request,$bookingid);

    if($res){
        return response()->json(['success'=>true,'data'=>$res],200);
    }else{
        return response()->json(['message'=>'message has not been sent. Please try again!!'],401);
    }

   }

   public function getchat(Request $request,$bookingid){

        $history = $request->history;
       
        $res = app(ChatsRepository::class)->chat($bookingid,$history);
        $res = (count($res)>0)?$res:[];
        return response()->json(['chat'=>$res],200);
   }
}