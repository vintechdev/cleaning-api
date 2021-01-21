<?php 
namespace App\Repository;
use App\Providerservicemaps;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Service;
use App\Promocodes;
class ProviderServiceMapRespository{

        public function GetServicePriceofProvider($serviceid,$providerid, $returnArray = true){
           
           
           $pdr=Providerservicemaps::leftJoin('services', function($join){
                  $join->on('services.id', '=', 'provider_service_maps.service_id');
                })->whereIn('provider_service_maps.service_id',$serviceid)
                ->where('provider_service_maps.provider_id',$providerid)->whereNotNull('provider_service_maps.amount')->get(['services.service_type','services.is_default_service','provider_service_maps.*']);
           if ($returnArray) {
               return $pdr->toarray();
           }
           return $pdr;
        }
        public function GetServicePrice($serviceid){
          if($serviceid){
             $services = Service::where('id',$serviceid)->where('active',1)->get()->toArray();
             return $services;
          }
        }

        public function GetServicesByProvider($pid)
        {
         return Providerservicemaps::with('service')->where('provider_service_maps.provider_id',$pid)->where('provider_service_maps.status',1)->get()->toarray();
        }
       
}


?>