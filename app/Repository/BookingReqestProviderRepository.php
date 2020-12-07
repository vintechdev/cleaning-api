<?php 
namespace App\Repository;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Bookingrequestprovider;
class BookingReqestProviderRepository{

    public function getBookingProvidersDetails($bookingid){

          $result = Bookingrequestprovider:: select(DB::raw('sum(booking_request_providers.status = "pending") as pending_count'),DB::raw('sum(booking_request_providers.status = "accepted") as accepted_count'),'booking_request_providers.*','users.first_name as provider_first_name','users.last_name as provider_last_name', 'users.profilepic as provider_profilepic','users.mobile_number as provider_mobile_number','users.email')->leftJoin('users', function($join){
            $join->on('users.id', '=', 'booking_request_providers.provider_user_id');
          })->where('booking_request_providers.booking_id',$bookingid)
          ->get()->toArray();
          dd($result);
          return $result;

    }

}

?>