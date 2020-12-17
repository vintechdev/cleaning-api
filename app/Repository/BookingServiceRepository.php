<?php 
namespace App\Repository;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Bookingservice;
class BookingServiceRepository{

    public function getServiceDetails($bookingid){

          $result = Bookingservice::leftJoin('services', function($join){
            $join->on('services.id', '=', 'booking_services.service_id');
          })->where('booking_services.booking_id',$bookingid)->get(['booking_services.*','services.name as service_name'])->toArray();
          return $result;

    }

}

?>