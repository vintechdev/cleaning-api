<?php

namespace App\Http\Controllers\Backend\API;

use App\Chats;

use App\User;
use Carbon\Carbon;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Repository\Eloquent\ChatsRepository;

/**
 * Class BookingJobsController
 * @package App\Http\Controllers\Backend\API
 */
class ChatsController extends Controller
{
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
        return response()->json(['success'=>true],200);
    }else{
        return response()->json(['message'=>'message has not been sent. Please try again!!'],401);
    }

   }

   public function getchat($bookingid){
        $res = app(ChatsRepository::class)->chat($bookingid);

        if($res){
            return response()->json(['success'=>true],200);
        }else{
            return response()->json(['message'=>'message has not been sent. Please try again!!'],401);
        }
   }
}