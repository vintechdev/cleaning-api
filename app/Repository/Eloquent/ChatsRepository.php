<?php

namespace App\Repository\Eloquent;

use App\Chats;
use App\Http\Resources\Chat;
use Illuminate\Http\Request;
use Auth;


class ChatsRepository extends AbstractBaseRepository
{
    protected function getModelClass(): string
    {
        return Chats::class;
    }

    public function addmessage(Request $request,$id)
    {
        $chat = new Chats();
        $chat->booking_id = $id;
        $chat->message = $request->message;
        $chat->sender_id = Auth::user()->id;
        $chat->receiver_id = $request->receiver_id;
        //$chat->isread =1;

        if($chat->save()){
            return $chat;
        }else{
            return false;
        }
    }

    public function chat($bookingid,$history)
    {
        if($history){
            $arr = Chats::where('booking_id',$bookingid)->orderBy('created_at','asc')->get()->toArray();
        }else{

            $arr = Chats::where('booking_id',$bookingid)->where('receiver_id',Auth::user()->id)->where('isread',0)->orderBy('created_at','asc')->get()->toArray();//
            Chats::where('booking_id',$bookingid)->where('sender_id','!=',Auth::user()->id)->where('isread',0)->update(['isread'=>1]);
        }
        return $arr;
    }
}