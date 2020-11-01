<?php

namespace App\Http\Controllers\Backend\API;

use App\OnceBookingAlternateDate;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\OnceBookingAlternateDateRequest;
use App\Http\Resources\OnceBookingAlternateDateCollection;
use App\Http\Resources\OnceBookingAlternateDate as OnceBookingAlternateDateResource;
use App\Http\Controllers\Controller;
use Auth;
use Hash;
use DB;

class OnceBookingAlternateDateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $onceBookingAlternateDates = OnceBookingAlternateDate::query();
        
		if ($request->has('id')) {
			$onceBookingAlternateDates = $onceBookingAlternateDates->where('id', 'LIKE', '%'.$request->get('id').'%');
		}
		if ($request->has('booking_id')) {
			$onceBookingAlternateDates = $onceBookingAlternateDates->where('booking_id', 'LIKE', '%'.$request->get('booking_id').'%');
		}
		if ($request->has('booking_date')) {
			$onceBookingAlternateDates = $onceBookingAlternateDates->where('booking_date', 'LIKE', '%'.$request->get('booking_date').'%');
		}
		if ($request->has('number_of_hours')) {
			$onceBookingAlternateDates = $onceBookingAlternateDates->where('number_of_hours', 'LIKE', '%'.$request->get('number_of_hours').'%');
		}
        $onceBookingAlternateDates = $onceBookingAlternateDates->paginate(20);
        return (new OnceBookingAlternateDateCollection($onceBookingAlternateDates));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(OnceBookingAlternateDateRequest $request, OnceBookingAlternateDate $onceBookingAlternateDate)
    {
        $onceBookingAlternateDate = OnceBookingAlternateDate::firstOrNew(['id' => $request->get('id')]);
        $onceBookingAlternateDate->id = $request->get('id');
		$onceBookingAlternateDate->booking_alternate_dates_uuid = $request->get('booking_alternate_dates_uuid');
		$onceBookingAlternateDate->booking_id = $request->get('booking_id');
		$onceBookingAlternateDate->booking_date = $request->get('booking_date');
		$onceBookingAlternateDate->number_of_hours = $request->get('number_of_hours');

        $onceBookingAlternateDate->save();

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
        $onceBookingAlternateDate = OnceBookingAlternateDate::find($request->get('id'));
        $onceBookingAlternateDate->delete();
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
        $onceBookingAlternateDate = OnceBookingAlternateDate::withTrashed()->find($request->get('id'));
        $onceBookingAlternateDate->restore();
        return response()->json(['no_content' => true], 200);
    }
}
