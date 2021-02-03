<?php

namespace App;
use App\Service;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Providerservicemaps extends Model
{
    use HasApiTokens, Notifiable;
    use Uuids;
    protected $table = 'provider_service_maps';
    use SoftDeletes;
    protected $fillable = ['id'];
    // public $incrementing = false;
    public function service()
    {
        return $this->belongsTo(Service::class,'service_id','id');
    }

    /**
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param int $hours
     * @return int
     */
    public function getProviderTotal(float $hours = null)
    {
        return $this
            ->getService()
            ->getTotalCost($hours, $this->amount);
    }

    public function GetTotal(){
        $priceperprovider  =[];
        $id = $request->get('serviceid');
        $servicetime = $request->get('servicetime');
        $provider_id = explode(',',$request->get('providerid'));
        $totaltime=0;
        foreach($provider_id as $pid){
            $pdr = Providerservicemaps::whereIn('service_id',$id)->where('provider_id',$pid)->get()->toarray();//->sum('amount');
           // dd($pdr);
           $totalserviceprice =0;
            foreach($pdr as $v){
                if($v['type']=='billingrateperhour'){
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
        $result['total_cost']=$totalprice;
        $result['total_time']=$totaltime;
        return Response::json($result);
    }
}
