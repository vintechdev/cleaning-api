<?php
namespace App\Services;
use App\Repository\ProviderServiceMapRespository;
use Response;
use Illuminate\Http\Request;

class TotalCostCalculation{

    public function __construct(ProviderServiceMapRespository $providerservicemap)
    {
        $this->providerservicemap = $providerservicemap;
    }
    public function GetHighestTotalPrice($servideid,$provider_id,$servicetime){
            $priceperprovider  =[];
            $result=[];
            $provider_id = explode(',',$provider_id);
            $totaltime=0;
            if(is_array($servideid)){
            foreach($provider_id as $pid){

                $pdr = $this->providerservicemap->GetServicePriceofProvider($servideid,$pid);
                $totalserviceprice =0;
                foreach($pdr as $v){
                    if($v['service_type']=='hourly'){
                        $time = (array_key_exists($v['service_id'],$servicetime))?$servicetime[$v['service_id']]:0;
                        $totalserviceprice += ($time*$v['amount']);
                        if($time==0){
                            $totalserviceprice += $v['amount'];
                        }
                       // echo $v['service_id'].'==>'.$v['provider_id'].'==>'.$totalserviceprice.'<br>';
                        $totaltime += $time;
                    }else{
                        $totalserviceprice += $v['amount'];
                    }
                    $priceperprovider[$pid] = $totalserviceprice;
                }
            }
            arsort($priceperprovider);
            $max = max($priceperprovider); // $max == 7
            $key = array_keys($priceperprovider, $max); 
            $totalprice = $max;
            $result['highvalproviderid'] = $key[0];
        }else{
            $services = $this->providerservicemap->GetServicePrice($servideid);
            dd($services);
            $price = $services[0]['service_cost'];
            $time = $servicetime;
            if($services[0]['service_type']=='hourly'){ 
                $totalprice = $time*$price;
            }else{
                $totalprice = $price;
            }
            $totaltime = $time;
        }
            $result['total_cost']=$totalprice;
            $result['total_time']=$totaltime;
            return $result;
    }

    public function PromoCodeDiscount(Request $request){
        $id = $request->get('serviceid');
        $servicetime = $request->get('servicetime');
        $providerid = $request->get('providerid');
        $promocode = $request->promocode;
        $categoryid = $request->servicecategory;
        $res = $this->GetHighestTotalPrice($id,$providerid,$servicetime);
       
        $total_amount = $res['total_cost'];
        $result=array();
        $arr = $this->providerservicemap->CheckPromocode($promocode,$categoryid);
        if(!empty($arr)){
                
            if( $arr[0]['discount_type']=='flat'){
                $discount_amount=$total_amount-$arr[0]['discount'];
            }else{
                $discount_amount=$total_amount-($total_amount*$arr[0]['discount'])/100;
            }
            $result['total_cost']=$total_amount;
            $result['discount']=$arr[0]['discount'];
            $result['final_cost']=$discount_amount;
            return ['data' => $result];
         }else{
            return ['data' => 'Promocode is not valid'];
         }
    }
}

?>