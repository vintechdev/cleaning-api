<?php 
namespace App\Repository;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Bookingservice;
use App\Booking;
use App\Servicecategory;

class BookingServiceRepository{

    public function getServiceDetails($bookingid){

          $result = Bookingservice::leftJoin('services', function($join){
            $join->on('services.id', '=', 'booking_services.service_id');
          })->where('booking_services.booking_id',$bookingid)
          ->get(['booking_services.*', 'services.is_default_service','services.name as service_name'])
          ->toArray();
          return $result;

    }

    public function BookingDetailsforMail($bookingid){


      $bdata = Booking::join('booking_status', 'bookings.booking_status_id', '=', 'booking_status.id')
                ->join('plans','bookings.plan_type','=','plans.id')
                ->join('users','users.id','=','bookings.user_id')
                ->select('bookings.*','plans.plan_name','users.first_name','users.email')
                ->where('bookings.id', $bookingid)
                ->get()->toArray();
      
      $data =[];
      $data['plan']=$bdata[0]['plan_name'];
      $data['name']=$bdata[0]['first_name'];
      $services = $this->getServiceDetails($bookingid);
    
     // $companyName = isset($default['company']) && !empty($default['company']) ? $default['company'] : __('Trade By Trade');
      $subject = 'Service Booked : '.$bookingid;
      $data['booking'] = $bdata[0];
      $data['services'] = $services;
     
      $data['userEmail'] =$bdata[0]['email'];
      $data['userName'] = $bdata[0]['first_name'];
      $data['subject'] = 'New Service Booked : '.$bookingid;
      
      return $data;

    }
    public function BookingDetailsforProviderEmail($bookingid,$user_id)
    {
      $bookingaddress = app(BookingAddressRepository::class)->Bookingaddress($bookingid);
      $userdetails = app(UserRepository::class)->getUserDetails($user_id);
      $services = $this->getServiceDetails($bookingid);
      $bookings = Booking::leftJoin('plans','plans.id','=','bookings.plan_type')->where('bookings.id',$bookingid)->get(['bookings.*','plans.plan_name'])->toArray();
      $data['bookings']=$bookings[0];
      

      $data['address']=$bookingaddress;
      $data['userdetails'] = $userdetails[0];
      $data['services'] = $services;
      return $data;
     
    }


    public function getBookingCategory($bookingId)
    {
        return Servicecategory::query()->select(['service_categories.*'])
        ->join('services','services.category_id', '=', 'service_categories.id')
        ->join('booking_services','services.id', '=', 'booking_services.service_id')
        ->where('booking_services.booking_id', $bookingId)
        ->first(); 
    }
   
  


}

?>