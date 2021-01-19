<?php

namespace App\Repository\Eloquent;

use App\Working_hours;

use App\Http\Resources\Chat;
use Illuminate\Http\Request;
use Auth;


class ProfileRepository extends AbstractBaseRepository
{
    protected function getModelClass(): string
    {
        return Working_hours::class;
    }

    public function createworkinghours($data)
    {
        $user_id = Auth::user()->id;
        if(count($data)>0){
            foreach($data as $k=>$v){
                $Working_hours = Working_hours::firstOrNew(['provider_id' =>$user_id,'working_days'=>$v['working_days']]);
                $Working_hours->provider_id = $user_id;
                $Working_hours->working_days = $v['working_days'];
                $Working_hours->start_time = $v['start_time'];
                $Working_hours->end_time =  $v['end_time'];
                $Working_hours->save();
            }
        }
        return true;
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