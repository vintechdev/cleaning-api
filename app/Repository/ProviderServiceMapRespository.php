<?php 
namespace App\Repository;
use App\Providerservicemaps;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Service;
use App\Promocodes;
class ProviderServiceMapRespository{

        public function GetServicePriceofProvider($serviceid,$providerid){
           
           $pdr=Providerservicemaps::leftJoin('services', function($join){
            $join->on('services.id', '=', 'provider_service_maps.service_id');
          })->whereIn('provider_service_maps.service_id',$serviceid)
          ->where('provider_service_maps.provider_id',$providerid)->get(['services.service_type','provider_service_maps.*'])->toarray();
         // dd($pdr);
           return $pdr;
        }
        public function GetServicePrice($serviceid){
        //  echo $serviceid;exit;
        //DB::enableQueryLog();
            $services = Service::where('id',$serviceid)->where('active',1)->get()->toArray();
          //  dd($services);
          // dd(DB::getQueryLog());
            return $services;
        }
        public function CheckPromocode($promocode,$categoryid){
            $res = Promocodes::where('name',$promocode)->where('category_id',$categoryid)->limit(1)->get()->toArray();
            return $res;
        }
}


?>