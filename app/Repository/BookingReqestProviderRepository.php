<?php 
namespace App\Repository;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Bookingrequestprovider;
class BookingReqestProviderRepository{

    public function getBookingProvidersCount($bookingid){
          $result = Bookingrequestprovider:: select(DB::raw('sum(booking_request_providers.status = "pending") as pending_count'),DB::raw('sum(booking_request_providers.status = "accepted") as accepted_count'))->where('booking_request_providers.booking_id',$bookingid)->get()->toArray();
          return $result;

    }
    public function getBookingAccptedProvidersDetails($bookingid){
        $result = Bookingrequestprovider::join('users', 'booking_request_providers.provider_user_id', '=', 'users.id')->where('booking_request_providers.booking_id',$bookingid)->where('booking_request_providers.status','accepted')
        ->get(['booking_request_providers.*','users.first_name as provider_first_name','users.last_name as provider_last_name', 'users.profilepic as provider_profilepic','users.mobile_number as provider_mobile_number','users.email'])->toArray();
        return $result;
    }
    public function getBookingPendingProvidersDetails($bookingid){
        $result = Bookingrequestprovider::join('users', 'booking_request_providers.provider_user_id', '=', 'users.id')->where('booking_request_providers.booking_id',$bookingid)->where('booking_request_providers.status','pending')
        ->get(['booking_request_providers.*','users.first_name as provider_first_name','users.last_name as provider_last_name', 'users.profilepic as provider_profilepic','users.mobile_number as provider_mobile_number','users.email'])->toArray();
        return $result;
    }

}

?>