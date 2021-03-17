<?php

namespace App\Http\Controllers\Backend\API;

use App\Announcement;
use App\Bookingstatus;
use App\Booking;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\AnnoucementRequest;
use App\Http\Resources\AnnoucementCollection;
use App\Http\Resources\Annoucement as AnnoucementResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use Hash;
use DB;

class AnnoucementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $annoucements = Announcement::query();

        if ($request->has('id')) {
            $annoucements = $annoucements->where('id', 'LIKE', '%'.$request->get('id'));
        }
        if ($request->has('type')) {
            $annoucements = $annoucements->where('type', 'LIKE', '%'.$request->get('type').'%');
        }
        if ($request->has('message')) {
            $annoucements = $annoucements->where('message', 'LIKE', '%'.$request->get('message').'%');
        }
        if ($request->has('location')) {
            $annoucements = $annoucements->where('location', 'LIKE', '%'.$request->get('location').'%');
        }
        $annoucements = $annoucements->paginate(20);
        return (new AnnoucementCollection($annoucements));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(AnnoucementRequest $request, Annoucement $annoucement)
    {
        $annoucement = Announcement::firstOrNew(['id' => $request->get('id')]);
        $annoucement->id = $request->get('id');
        $annoucement->annoucement_uuid = $request->get('annoucement_uuid');
        $annoucement->type = $request->get('type');
        $annoucement->message = $request->get('message');
        $annoucement->location = $request->get('location');

        $annoucement->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }

    //for get dashboard
    public function getdashboard(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $todays_date = date("Y-m-d");
        // print_r($user_id);exit;

        $data['annoucements'] = DB::table('announcements')
                                    ->where('user_id', $user_id)
                                    ->where('status', 'unread')
                                    ->get();

        $data['appointments'] = DB::table('bookings')
                                    ->join('booking_status', 'bookings.booking_status_id', '=', 'booking_status.id')
                                    ->join('booking_services', 'bookings.id', '=', 'booking_services.booking_id')
                                    ->join('services', 'booking_services.service_id', '=', 'services.id')
                                    ->join('booking_request_providers', 'bookings.id', '=', 'booking_request_providers.booking_id')
                                    ->join('users', 'booking_request_providers.provider_user_id', '=', 'users.id')
                                    ->select('bookings.*', 'booking_status.status as booking_status', 'services.name as service_name', 'booking_services.initial_number_of_hours', 'booking_services.initial_service_cost', 'users.first_name as provider_first_name', 'users.last_name as provider_last_name')
                                    ->where('user_id', $user_id)
                                    ->orderBy('id', 'desc')
                                    ->take(5)
                                    ->get();

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

        // $Annoucement = Annoucement::query();

		// if ($user_id) {
		// 	$Annoucement = $Annoucement->where('user_id', 'LIKE', '%'.$user_id);
		// }

        // $Annoucement = $Annoucement->paginate(20);
        // return (new AnnoucementCollection($Annoucement));
    }

    // for change announcement status to read
    public function change_announcement_status(Request $request, $uuid){
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);

        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

        $user = Auth::user();
        $user_id = $user->id;
        // dd($user_id);

        $announcement = Annoucement::firstOrNew(['uuid' => $uuid]);
        $announcement->status = $request->get('status');
        $announcement->save();

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
        $annoucement = Announcement::find($request->get('id'));
        $annoucement->delete();
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
        $annoucement = Announcement::withTrashed()->find($request->get('id'));
        $annoucement->restore();
        return response()->json(['no_content' => true], 200);
    }
}
