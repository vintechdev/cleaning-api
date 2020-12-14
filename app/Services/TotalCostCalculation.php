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
            $servicewiseprice = [];
            $provider_id = explode(',',$provider_id);
            $totaltime=0;
            $is_default_service_id ='';
            $provider_service_price = [];
            $provider_service_cost_with_time = [];
            if(is_array($servideid)){
                    
                foreach($provider_id as $pid){
                    $serpr = [];
                    $sertotprice = [];
                    $pdr = $this->providerservicemap->GetServicePriceofProvider($servideid,$pid);
                    $totalserviceprice =0;
                    foreach($pdr as $v){
                        if($v['is_default_service']==1){$is_default_service_id=$v['service_id'];}
                        if($v['service_type']=='hourly'){
                            
                            $time = (array_key_exists($v['service_id'],$servicetime))?$servicetime[$v['service_id']]:0;

                            $cost = ($time*$v['amount']);
                            $sertotprice[$v['service_id']]=$cost;
                            $totalserviceprice +=  $cost;
                            if($time==0){
                                $totalserviceprice += $v['amount'];
                            }
                        // echo $v['service_id'].'==>'.$v['provider_id'].'==>'.$totalserviceprice.'<br>';
                            $totaltime += $time;
                        }else{
                            $totalserviceprice += $v['amount'];
                            $sertotprice[$v['service_id']]=$v['amount'];
                        }
                        
                        $serpr[$v['service_id']]=$v['amount'];
                        $servicewiseprice[$v['service_id']][]= array($pid=>$v['amount']);
                        
                        $priceperprovider[$pid] = $totalserviceprice;
                    }
                   // $servicewiseprice[$v['service_id']][]= ;
                   $provider_service_price[$pid]= $serpr;
                   $provider_service_cost_with_time[$pid]= $sertotprice;
                }
                $totaltime = array_sum($servicetime);
                arsort($priceperprovider);
                $max = max($priceperprovider); // $max == 7

                //check highest price provider
              
               $highestcount = count(array_keys($priceperprovider, $max));
               if($highestcount>1) {
                   //if same price offers by >2 providers check default service price;
                   $keys = array_keys($arr, $max );
                   $high_def_ser_price_provider_id = $this->GetHighestDefServicePriceProvider($keys,$servicewiseprice[$is_default_service_id]);
                }else{
                    $key = array_keys($priceperprovider, $max); 
                    $high_def_ser_price_provider_id = $key[0];
                }
              
                $totalprice = $max;
                $result['highvalproviderid'] = $high_def_ser_price_provider_id;
                $result['provider_wise_service_price'] = $provider_service_price;
                $result['provider_service_cost_with_time'] = $provider_service_cost_with_time;
                $result['total_cost']=$totalprice;
                $result['total_time']=$totaltime;
                return $result;
            }else{
                $services = $this->providerservicemap->GetServicePrice($servideid);
            //    dd($services);
                $price = $services[0]['service_cost'];
                $time = $servicetime;
                if($services[0]['service_type']=='hourly'){ 
                    $totalprice = $time*$price;
                }else{
                    $totalprice = $price;
                }
                $totaltime = $time;
                $result['total_cost']=$totalprice;
                $result['total_time']=$totaltime;
                return $result;
            }
            
         //   return $result;
    }

    public function GetHighestDefServicePriceProvider($keys,$servicewiseprice)
    {
        $defserprice = [];
        //dd($servicewiseprice);
        foreach($servicewiseprice as $key=>$val){
               $firstKey = array_key_first($val);
               $defserprice[$firstKey]=$val[$firstKey];
        }
        $maxprice = max($defserprice);
        $key = array_keys($defserprice, $maxprice); 
        return $key[0];
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