<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Repository\BookingServiceRepository;
use App\Repository\BookingReqestProviderRepository;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard(){
      //  $user = Auth::user();
        $user_id = Auth::user()->id;
        $todays_date = date("Y-m-d");

        $data['annoucements'] = DB::table('announcements')
        ->where('user_id', $user_id)
        ->where('status', 'unread')
        ->get();

        $bookings = DB::table('bookings')
      //  ->join('booking_status', 'bookings.booking_status_id', '=', 'booking_status.id')
       // ->join('booking_services', 'bookings.id', '=', 'booking_services.booking_id')
       // ->join('services', 'booking_services.service_id', '=', 'services.id')
       // ->join('booking_request_providers', 'bookings.id', '=', 'booking_request_providers.booking_id')
      //  ->join('users', 'booking_request_providers.provider_user_id', '=', 'users.id')
      //  ->select('bookings.*', 'booking_status.status as booking_status', 'services.name as service_name', 'booking_services.initial_number_of_hours', 'booking_services.initial_service_cost', 'users.first_name as provider_first_name', 'users.last_name as provider_last_name')
        ->where('user_id', $user_id)
        ->orderBy('id', 'desc')
        ->take(5)
        ->get()->toArray();
        $booking = [];

        if(count($bookings)>0){
            foreach($bookings as $key=>$val){
                $id = $val->id;
                $services = app(BookingServiceRepository::class)->getServiceDetails($id);
                $providerscount = app(BookingReqestProviderRepository::class)->getBookingProvidersCount($id);
                if( $providerscount[0]['accepted_count']>0){
                    $providers = app(BookingReqestProviderRepository::class)->getBookingAccptedProvidersDetails($id);
                }else{
                    $providers = app(BookingReqestProviderRepository::class)->getBookingPendingProvidersDetails($id);
                }
                $booking[$id] = (array)$val;
                $booking[$id]['providers'] = $providers;
                $booking[$id]['services']=$services;

            }


}
$data['bookings'] = $booking;

       

$data['todays_appointments'] = DB::table('bookings')
                ->join('booking_status', 'bookings.booking_status_id', '=', 'booking_status.id')
                ->join('booking_services', 'bookings.id', '=', 'booking_services.booking_id')
                ->join('services', 'booking_services.service_id', '=', 'services.id')
                ->join('booking_request_providers', 'bookings.id', '=', 'booking_request_providers.booking_id')
                ->join('users', 'booking_request_providers.provider_user_id', '=', 'users.id')
                ->select('bookings.*', 'booking_status.status as booking_status', 'services.name as service_name', 'booking_services.initial_number_of_hours', 'booking_services.initial_service_cost', 'users.first_name as provider_first_name', 'users.last_name as provider_last_name')
                ->where('user_id', $user_id)
                ->where('booking_date', $todays_date)
                ->orderBy('id', 'desc')
                ->take(5)
                ->get();
// print_r($users);exit;

return response()->json(['data' => $data]);

    }
}
