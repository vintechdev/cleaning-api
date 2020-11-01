<?php

namespace App\Http\Controllers\Backend\API;

use App\Bookingrequestprovider;
use App\Booking;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\BookingrequestproviderRequest;
use App\Http\Resources\BookingrequestproviderCollection;
use App\Http\Resources\Bookingrequestprovider as BookingrequestproviderResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use Hash;
use DB;

class BookingrequestprovidersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bookingrequestproviders = Bookingrequestprovider::query();
        
		if ($request->has('booking_id')) {
			$bookingrequestproviders = $bookingrequestproviders->where('booking_id', 'LIKE', '%'.$request->get('booking_id').'%');
		}
		if ($request->has('provider_user_id')) {
			$bookingrequestproviders = $bookingrequestproviders->where('provider_user_id', 'LIKE', '%'.$request->get('provider_user_id').'%');
		}
		if ($request->has('status')) {
			$bookingrequestproviders = $bookingrequestproviders->where('status', 'LIKE', '%'.$request->get('status').'%');
		}
		if ($request->has('provider_comment')) {
			$bookingrequestproviders = $bookingrequestproviders->where('provider_comment', 'LIKE', '%'.$request->get('provider_comment').'%');
		}
		if ($request->has('visible_to_enduser')) {
			$bookingrequestproviders = $bookingrequestproviders->where('visible_to_enduser', 'LIKE', '%'.$request->get('visible_to_enduser').'%');
		}
        $bookingrequestproviders = $bookingrequestproviders->paginate(20);
        return (new BookingrequestproviderCollection($bookingrequestproviders));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(BookingrequestproviderRequest $request, Bookingrequestprovider $bookingrequestprovider)
    {
        $bookingrequestprovider = Bookingrequestprovider::firstOrNew(['id' => $request->get('id')]);
        $bookingrequestprovider->id = $request->get('id');
		$bookingrequestprovider->booking_request_providers_uuid = $request->get('booking_request_providers_uuid');
		$bookingrequestprovider->booking_id = $request->get('booking_id');
		$bookingrequestprovider->provider_user_id = $request->get('provider_user_id');
		$bookingrequestprovider->status = $request->get('status');
		$bookingrequestprovider->provider_comment = $request->get('provider_comment');
		$bookingrequestprovider->visible_to_enduser = $request->get('visible_to_enduser');

        $bookingrequestprovider->save();

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
        $bookingrequestprovider = Bookingrequestprovider::find($request->get('id'));
        $bookingrequestprovider->delete();
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
        $bookingrequestprovider = Bookingrequestprovider::withTrashed()->find($request->get('id'));
        $bookingrequestprovider->restore();
        return response()->json(['no_content' => true], 200);
    }

    // for provider accept booking api
    public function provider_accept(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

        $user = Auth::user();
        $user_id = $user->id;

        $Booking = new Booking();
        $Booking = $Booking->where('uuid', $uuid)->first();
        $booking_id = $Booking->id;

        $affected = DB::table('booking_request_providers')
        ->where('booking_id', $booking_id)
        ->where('provider_user_id', $user_id)
        ->update(['status' => 'accepted']);
        
        $affected = DB::table('booking_request_providers')
              ->where('booking_id', $booking_id)
              ->where('provider_user_id', '!=', $user_id)
              ->update(['status' => 'missed']);

        // $bookingrequestprovider = Bookingrequestprovider::firstOrNew(['booking_id' => $booking_id]);
        // $bookingrequestprovider->status = 'accepted';
        // $bookingrequestprovider->save();
        // $lastinsertedid = $bookingrequestprovider->id;

        $Booking = Booking::firstOrNew(['id' => $booking_id]);
        $Booking->booking_status_id = 2;
        $Booking->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }

    // for provider reject booking api
    public function provider_reject(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

        $user = Auth::user();
        $user_id = $user->id;

        $Booking = new Booking();
        $Booking = $Booking->where('uuid', $uuid)->first();
        $booking_id = $Booking->id;

        $affected = DB::table('booking_request_providers')
        ->where('booking_id', $booking_id)
        ->where('provider_user_id', $user_id)
        ->update(['status' => 'rejected']);

        $affected = DB::table('booking_request_providers')
              ->where('booking_id', $booking_id)
              ->where('provider_user_id', '!=', $user_id)
              ->update(['status' => 'missed']);

        // $bookingrequestprovider = Bookingrequestprovider::firstOrNew(['booking_id' => $booking_id]);
        // $bookingrequestprovider->status = 'rejected';
        // $bookingrequestprovider->save();

        $Booking = Booking::firstOrNew(['id' => $booking_id]);
        $Booking->booking_status_id = 6;
        $Booking->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }
}
