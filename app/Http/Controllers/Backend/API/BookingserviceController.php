<?php

namespace App\Http\Controllers\Backend\API;

use App\Bookingservice;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\BookingserviceRequest;
use App\Http\Resources\BookingserviceCollection;
use App\Http\Resources\Bookingservice as BookingserviceResource;
use App\Http\Controllers\Controller;

class BookingserviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function add_multipal_service(Request $request)
    {
        $record = $request->records;
        
        if(! empty($record))
        {
               
            foreach($record as $key => $service)
            {
            $bookingservice = new Bookingservice;
            $bookingservice->booking_id = $service['booking_id'];
            $bookingservice->service_id = $service['service_id'];
            $bookingservice->initial_number_of_hours = $service['initial_number_of_hours'];
            $bookingservice->initial_service_cost = $service['initial_service_cost'];
            $bookingservice->final_number_of_hours = $service['final_number_of_hours'];
            $bookingservice->final_service_cost = $service['final_service_cost'];

            $bookingservice->save();            
            }

            return response()->json(['saved' => true], 201);


        }
    else{
            return response()->json(['saved' => false], 404);
        }

        
    
    }
    public function index(Request $request)
    {
        $bookingservices = Bookingservice::query();
        
		if ($request->has('booking_id')) {
			$bookingservices = $bookingservices->where('booking_id', 'LIKE', '%'.$request->get('booking_id').'%');
		}
		if ($request->has('service_id')) {
			$bookingservices = $bookingservices->where('service_id', 'LIKE', '%'.$request->get('service_id').'%');
		}
		if ($request->has('initial_number_of_hours')) {
			$bookingservices = $bookingservices->where('initial_number_of_hours', 'LIKE', '%'.$request->get('initial_number_of_hours').'%');
		}
		if ($request->has('initial_service_cost')) {
			$bookingservices = $bookingservices->where('initial_service_cost', 'LIKE', '%'.$request->get('initial_service_cost').'%');
		}
		if ($request->has('final_number_of_hours')) {
			$bookingservices = $bookingservices->where('final_number_of_hours', 'LIKE', '%'.$request->get('final_number_of_hours').'%');
		}
		if ($request->has('final_service_cost')) {
			$bookingservices = $bookingservices->where('final_service_cost', 'LIKE', '%'.$request->get('final_service_cost').'%');
		}
        $bookingservices = $bookingservices->paginate(20);
        return (new BookingserviceCollection($bookingservices));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(BookingserviceRequest $request, Bookingservice $bookingservice)
    {
        $bookingservice = Bookingservice::firstOrNew(['id' => $request->get('id')]);
        $bookingservice->id = $request->get('id');
		$bookingservice->booking_service_uuid = $request->get('booking_service_uuid');
		$bookingservice->booking_id = $request->get('booking_id');
		$bookingservice->service_id = $request->get('service_id');
		$bookingservice->initial_number_of_hours = $request->get('initial_number_of_hours');
		$bookingservice->initial_service_cost = $request->get('initial_service_cost');
		$bookingservice->final_number_of_hours = $request->get('final_number_of_hours');
		$bookingservice->final_service_cost = $request->get('final_service_cost');

        $bookingservice->save();

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
        $bookingservice = Bookingservice::find($request->get('id'));
        $bookingservice->delete();
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
        $bookingservice = Bookingservice::withTrashed()->find($request->get('id'));
        $bookingservice->restore();
        return response()->json(['no_content' => true], 200);
    }
}
