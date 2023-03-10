<?php

namespace App\Repository\Eloquent;

use App\Working_hours;
use App\Providerpostcodemap;
use App\Providerservicemaps;
use App\StripeUserMetadata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProfileRepository extends AbstractBaseRepository
{

    public function CheckProviderStripeVarified(){
        $user_id = Auth::user()->id;
        $payment = StripeUserMetadata::where('user_id',$user_id)->where('stripe_connect_account_verified',1)->get()->count();
        return $payment;
    }

    public function CheckProfileCompleted()
    {
        $user_id = Auth::user()->id;

        //check working hours 
        $wh  = Working_hours::where('provider_id',$user_id)->get()->count();
        
        //service mapping
        $ps  = Providerservicemaps::where('provider_id',$user_id)->get()->count();

        //post code mapping
        $pc  = Providerpostcodemap::where('provider_id',$user_id)->get()->count();

        //stripe payment 
        $payment = StripeUserMetadata::where('user_id',$user_id)->where('stripe_connect_account_verified',1)->get()->count();

        return ['working_hour'=>$wh,'provider_service'=>$ps,'postcode'=>$pc,'payment'=>$payment];

    }

    protected function getModelClass(): string
    {
        return Working_hours::class;
    }

    public function getproviderpostcode(Request $request)
    {
        $search = $request->input('search');
        $user_id = $request->get('user_id') && $request->hast('isAdmin') ? $request->get('user_id') : Auth::user()->id;

        $query = Providerpostcodemap::query()->with(['postcode' => function($query) use ($search) {
            if ($search!='' ) {
                $query->where('postcode', 'like', '%' . $search . '%');
            }
        }]);

        $res = $query->where('provider_id', $user_id)->get()->toArray();

        return $res;
    }

    public function addproviderpostcode($postcode, $userId = null) {
        $user_id = $userId ? $userId : Auth::user()->id;
        $Providerpostcodemap = Providerpostcodemap::query()->firstOrNew(['provider_id' =>$user_id,'postcode_id'=>$postcode]);
        $Providerpostcodemap->provider_id = $user_id;
        $Providerpostcodemap->postcode_id = $postcode;
     
        if($Providerpostcodemap->save()){
            return Providerpostcodemap::query()->with('postcode')
                ->where('id',$Providerpostcodemap->id)->get()->toArray();
        }else{
            return false;
        }
    }
    public function deleteproviderpostcode($postcode)
    {
        # code...
        $query = Providerpostcodemap::query();
        $arr = $query->where('id', $postcode)->delete();

        if($arr){
            return true;
        }else{
            return false;
        }
    }

    public function createworkinghours($data, $userId = null)
    {
        $user_id = $userId ? $userId : Auth::user()->id;
        $days = array('monday','tuesday','wednesday','thursday','friday','saturnday','sunday');
        if(count($data)>0){
           $postday = array_column($data,'working_days') ;
        //   dd($postday);
            foreach($data as $k=>$v){

                $Working_hours = Working_hours::updateOrCreate(
                    ['provider_id' =>$user_id, 'working_days'=>$v['working_days']],
                    ['start_time' => $v['start_time'], 'end_time' => $v['end_time']]
                );
            }
            $diff =  array_diff($days, $postday );
            if(count($diff)>0){
                foreach($diff as $k=>$v){
                    $Working_hours = Working_hours::where(['provider_id' =>$user_id,'working_days'=>$v])->forceDelete();
                }
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