<?php 
namespace App\Repository;
use App\Providerservicemaps;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Service;
use App\Promocodes;
use App\User;
use App\Customeruser;
class UserRepository{

    public function getProviderDetails($id)
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
        public function GetServicePrice($serviceid){
          if($serviceid){
             $services = Service::where('id',$serviceid)->where('active',1)->get()->toArray();
             return $services;
          }
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
}


?>