<?php 
namespace App\Repository;
use App\Providerservicemaps;
use Illuminate\Support\Facades\Auth;
use App\Service;
use Illuminate\Http\Request;
use App\Servicecategory;
class ProviderServiceMapRespository{

   public function save_provider_servicemap(Request $request)
   {
      # code...
      
      if($request->has('data')){
         $data = $request->data;
         $pid = $request->get('user_id') && session()->has('isAdmin') ? $request->get('user_id') : Auth::id();
         $service_ids = array_column($data,'service_id');
        
         // $service_ids = array_values($service_ids);
         //  dd($service_ids);
          $services = [];
         foreach($data as $k=>$v){
             if (!isset($services[$v['service_id']])) {
                 $services[$v['service_id']] = Service::find($v['service_id']);
                 if (!$services[$v['service_id']]) {
                     continue;
                 }
             }
             /** @var Service $service */
             $service = $services[$v['service_id']];

             if (!$service->getAllowPriceOverride() && $v['amount']) {
                 $v['amount'] = null;
             }

             $serviceType = ($service->getServiceType() == Service::SERVICE_TYPE_HOURLY) ?
                 Providerservicemaps::SERVICE_TYPE_HOURLY:
                 Providerservicemaps::SERVICE_TYPE_ONCEOFF;

             $Providerservicemaps = Providerservicemaps::updateOrInsert(
                 ['provider_id' =>$pid, 'service_id'=>$v['service_id']],
                 ['amount' => $v['amount'],'status'=>1, 'type' => $serviceType]
             );
         }
         Providerservicemaps::whereNotIn('service_id',  $service_ids)->where('provider_id',$pid)->forceDelete();
         return true;
      }
      return false;


   }
   public function getServiceCategory(Request $request)
   {
      # code...
      $user = auth()->user();
      
      $servicecategories = Servicecategory::with(['services'=>function($q){
         $q->where('active',1);
      }])->where('active','1');
      return $servicecategories->get()->toArray();
   }
        public function GetServicePriceofProvider($serviceid,$providerid, $returnArray = true){
           
           
           $pdr=Providerservicemaps::leftJoin('services', function($join){
                  $join->on('services.id', '=', 'provider_service_maps.service_id');
                })->whereIn('provider_service_maps.service_id',$serviceid)
                ->where('provider_service_maps.provider_id',$providerid)->get(['services.service_type','services.is_default_service','provider_service_maps.*']);
           if ($returnArray) {
               return $pdr->toarray();
           }
           return $pdr;
        }

        public function GetServicesByProvider($pid, $categoryId = null)
        {
            $maps =  Providerservicemaps::with('service');
            if ($categoryId) {
                $maps->leftJoin('services', function ($join) {
                    $join->on('services.id', '=', 'provider_service_maps.service_id');
                });
            }
            $maps->where('provider_service_maps.provider_id',$pid)->where('provider_service_maps.status',1);
            if ($categoryId) {
                $maps->where('services.category_id', $categoryId);
            }

            $maps = $maps->get()->toArray();

            foreach ($maps as &$map) {
                if ($map['service']['allow_price_override'] == 0 || is_null($map['amount'])) {
                    $map['amount'] = $map['service']['service_cost'];
                }
            }

            return $maps;
        }

       
       
}


?>