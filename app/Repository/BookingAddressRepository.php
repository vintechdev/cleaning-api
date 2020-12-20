<?php

namespace App\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Customeruser;
use App\Bookingaddress;

class BookingAddressRepository{

    public function Bookingaddress($bookingid)
    {
        # code...
       
        $arr = Bookingaddress::where('booking_id',$bookingid)->get()->toArray();
        return $arr[0];
        

    }

}
?>