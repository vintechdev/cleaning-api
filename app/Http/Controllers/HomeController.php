<?php

namespace App\Http\Controllers;

use App\Services\Bookings\BookingJobsManager;
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

        $data['annoucements'] = DB::table('announcements')
        ->where('user_id', $user_id)
        ->where('status', 'unread')
        ->get();

return response()->json(['data' => $data]);

    }
}
