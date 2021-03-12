<?php 
namespace App\Repository;

use App\Providerservicemaps;
use App\Promocodes;
use App\User;
use App\Customeruser;
use App\UserNotification;
use App\Bookingrequestprovider;

class UserRepository{

    public function getProviderDetails($id)
    {
        # code...
        $res = User::where('id',$id)->get()->toArray();
        return $res;
    }

    public function getUserDetails($id)
    {
        # code...
        $res = User::where('id',$id)->get()->toArray();
        return $res;
    }

        public function GetServicePriceofProvider($serviceid,$providerid){
           
           $pdr=Providerservicemaps::leftJoin('services', function($join){
                  $join->on('services.id', '=', 'provider_service_maps.service_id');
                })->whereIn('provider_service_maps.service_id',$serviceid)
                ->where('provider_service_maps.provider_id',$providerid)->get(['services.service_type','services.is_default_service','provider_service_maps.*'])->toarray();
           return $pdr;
        }

        public function CheckPromocode($promocode,$categoryid){
            $res = Promocodes::where('name',$promocode)->where('category_id',$categoryid)->limit(1)->get()->toArray();
            return $res;
        }
        public function getAgencyData()
        {
          # code...
          $users = Customeruser::where('providertype','agency')->pluck('id')->toArray();
          return $users;
        }

        public function getUserNotification($user_id,$nid){
        
            $notification = UserNotification::where('user_id',$user_id)->where('notification_id',$nid)->first()->toarray();
            return $notification;
        }

        //get completed booking statitics
        public function getdashboardstatistics()
        {
         $query = Bookingrequestprovider::join('bookings','booking_id', '=', 'bookings.id');
        
         $query1 = clone $query;
         $query2 = clone $query;
         $cost = $query->where('bookings.booking_status_id',4)->sum('bookings.final_cost');
         $totalcompletedbooking =  $query1->where('bookings.booking_status_id',4)->count();

        //total customer
        $totalcustomer = $query2->where('bookings.booking_status_id','!=',1)->where('bookings.booking_status_id','!=',6)->distinct('bookings.user_id')->count('bookings.user_id');


         return ['total_amount'=>$cost,'completedbooking'=>$totalcompletedbooking,'totalcustomer'=>$totalcustomer];
        }
        
}


?>