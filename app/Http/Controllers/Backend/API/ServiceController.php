<?php

namespace App\Http\Controllers\Backend\API;

use App\Service;
use App\Providerservicemaps;
use App\Bookingservice;
use App\Booking;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\ServiceRequest;
use App\Http\Resources\ServiceCollection;
use App\Http\Resources\Service as ServiceResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use Hash;
use DB;
use Input;
use Response;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function get_default_service(Request $request)
    {
    $service_name=$request->get('service_name');
    $provider_id=$request->get('provider_id');
    $users = DB::select("SELECT              
                            name,
                            is_default_service,
                            provider_service_maps.provider_id,
                            provider_service_maps.status
                            FROM services 
                              INNER JOIN provider_service_maps
                                ON provider_service_maps.service_id = services.id
                                WHERE services.is_default_service=1
                                AND provider_service_maps.status=1
                                AND services.name='$service_name'
                                AND provider_service_maps.provider_id='$provider_id'");
                            
        return response()->json(['data' => $users],200);

    }
    //---get total service price calculation //


    public function geserviceprice(Request $request){
       
        $id = $request->get('serviceid');
        $servicetime = $request->get('servicetime');
        $totaltime = 0;
        if(is_array($id)){
            $services = Service::whereIn('id',$id)->where('active',1)->get()->toArray();
            $totalprice = 0;
            foreach($services as $k=>$v){
                $price = $v['service_cost'];
                $time = $servicetime[$v['id']];
                $totaltime += $time;

                if($v['service_type']=='hourly'){ 
                    $total = $time*$price;
                }else{
                    $total = $price;
                }
                $totalprice += $total;
            }
        }else{
            $services = Service::where('id',$id)->where('active',1)->get()->toArray();
            $price = $services[0]['service_cost'];
            $time = $request->get('timeslot');
            if($services[0]['service_type']=='hourly'){ 
                $totalprice = $time*$price;
            }else{
                $totalprice = $price;
            }
            $totaltime = $time;
        }
        $result['total_cost']=$totalprice;
        $result['total_time']=$totaltime;
        /*if($request->has('promocode') && $request->has('categoryid')){
            $total_amount = $totalprice;
            $promocode = $request->get('promocode');
            $categoryid = $request->get('categoryid');
             $sql = DB::select("SELECT * FROM promocodes WHERE name='$promocode' and category_id=$categoryid limit 0,1");
         
             if(!empty($sql)){
               // foreach ($sql as $row){

                    $promocode_discount=$sql[0]->discount;
                    $discount_type = $sql[0]->discount_type;
               // }
                if( $discount_type=='flat'){
                    $discount_amount=$total_amount-$promocode_discount;
                }else{
                    $discount_amount=$total_amount-($total_amount*$promocode_discount)/100;
                }
                $result['discount']=$promocode_discount;
                $result['final_cost']=$discount_amount;
               
             }
        
        }*/
        if(is_array($id)){
            return $result;
        }else{
            return Response::json($result);
        }
    }
    public function index(Request $request)
    {
        $services = Service::query();
        
        if ($request->has('id')) {
			$services = $services->where('id', 'LIKE', '%'.$request->get('id').'%');
        }
        if ($request->has('uuid')) {
			$services = $services->where('uuid', 'LIKE', '%'.$request->get('uuid').'%');
		}
		if ($request->has('category_id')) {
			$services = $services->where('category_id', 'LIKE', '%'.$request->get('category_id').'%');
		}
		if ($request->has('name')) {
			$services = $services->where('name', 'LIKE', '%'.$request->get('name').'%');
		}
		if ($request->has('description')) {
			$services = $services->where('description', 'LIKE', '%'.$request->get('description').'%');
		}
		if ($request->has('image')) {
			$services = $services->where('image', 'LIKE', '%'.$request->get('image').'%');
		}
		if ($request->has('is_default_service')) {
			$services = $services->where('is_default_service', 'LIKE', '%'.$request->get('is_default_service').'%');
		}
		if ($request->has('active')) {
			$services = $services->where('active', 'LIKE', '%'.$request->get('active').'%');
		}
		if ($request->has('service_type')) {
			$services = $services->where('service_type', 'LIKE', '%'.$request->get('service_type').'%');
		}
		if ($request->has('service_cost')) {
			$services = $services->where('service_cost', 'LIKE', '%'.$request->get('service_cost').'%');
		}
        $services = $services->paginate(20);
        return (new ServiceCollection($services));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(ServiceRequest $request, Service $service)
    {
        $service = Service::firstOrNew(['id' => $request->get('id')]);
        $service->id = $request->get('id');
		$service->service_uuid = $request->get('service_uuid');
		$service->category_id = $request->get('category_id');
		$service->name = $request->get('name');
		$service->description = $request->get('description');
		if ($request->file('image')) {
			$service->image = $this->upload($request->file('image'), 'services')->getFileName();
		} else {
			$service->image = $service->image;
		}
		$service->is_default_service = $request->get('is_default_service');
		$service->active = $request->get('active');
		$service->service_type = $request->get('service_type');
		$service->service_cost = $request->get('service_cost');

        $service->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }

    //for add services
    public function addservices(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            'service_name' => 'required',
            'service_duration' => 'required|numeric',
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

        $service_name = $request->get('service_name');

        $service = new Service();
        $service = $service->where('name', $service_name)->first();
        $service_id = $service->id;

        $booking = new Booking();
        $booking = $booking->where('uuid', $uuid)->first();
        $booking_id = $booking->id;

        // $service = Service::firstOrNew(['id' => $request->get('id')]);
        // $service->id = $request->get('id');
		// $service->uuid = $request->get('uuid');
		// $service->name = $request->get('service_name');
        // $service->save();
        
        // $lastinsertedid = $service->id;
        // dd($lastinsertedid);
        
        $Bookingservice = new Bookingservice();
        // $Bookingservice->service_id = $lastinsertedid;
		$Bookingservice->uuid = $request->get('uuid');
		$Bookingservice->service_id = $service_id;
		$Bookingservice->booking_id = $booking_id;
        $Bookingservice->initial_number_of_hours = $request->get('service_duration');
        $Bookingservice->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }
    public function saveservices(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required',
            'status' => 'required'
        ]);

        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }
        $user = Auth::user();
        $user_id = $user->id;
        $service_id=$request->get('service_id');
        $serviceid_arr = explode (",", $service_id); 
        $status=$request->get('status'); 
        $status_arr = explode (",", $status);
        $i=-1;
        foreach ($serviceid_arr as $sku){ 
             $i++;
        $servicedata = Service::firstOrNew(['id' => $sku]);
        $service = Providerservicemaps::firstOrNew(['id' => $request->get('id')]);
        $serviceareadata = Providerservicemaps::firstOrNew(['service_id' => $sku,'provider_user_id' => $user_id]);
            if($serviceareadata['id'] == NULL)
            {
                $service->id = $request->get('id');
                $service->uuid = $request->get('uuid');
                $service->provider_user_id = $user_id;
                $service->service_id=$sku;
                $service->status=$status_arr[$i];
                $service->amount=$servicedata['service_cost'];
                $service->save();
            }
            else
            {
                $serviceareadata->amount = $servicedata['service_cost'];
                $serviceareadata->status=$status_arr[$i];
                $serviceareadata->save();
            }
                }
       
        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }

    //for update services by uuid
    public function editservices(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            'service_name' => 'required',
            'service_duration' => 'required|numeric',
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

        $service_name = $request->get('service_name');

        $service = new Service();
        $service = $service->where('name', $service_name)->first();
        $service_id = $service->id;

        // $service = Service::firstOrNew(['uuid' => $uuid]);
        // $service->name = $request->get('service_name');
        // $service->save();
        
        // $lastinsertedid = $service->id;
        
        $Bookingservice = Bookingservice::firstOrNew(['uuid' => $uuid]);
        // $Bookingservice->service_id = $lastinsertedid;
		// $Bookingservice->uuid = $request->get('uuid');
        $Bookingservice->service_id = $service_id;
        $Bookingservice->initial_number_of_hours = $request->get('service_duration');
        $Bookingservice->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $service = Service::find($request->get('id'));
        $service->delete();
        return response()->json(['no_content' => true], 200);
    }

    /**
     * Restore the specified resource to storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        $service = Service::withTrashed()->find($request->get('id'));
        $service->restore();
        return response()->json(['no_content' => true], 200);
    }
}
