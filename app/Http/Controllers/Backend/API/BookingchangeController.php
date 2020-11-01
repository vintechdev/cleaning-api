<?php

namespace App\Http\Controllers\Backend\API;

use App\Bookingchange;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\BookingchangeRequest;
use App\Http\Resources\BookingchangeCollection;
use App\Http\Resources\Bookingchange as BookingchangeResource;
use App\Http\Controllers\Controller;

class BookingchangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bookingchanges = Bookingchange::query();
        
		if ($request->has('booking_id')) {
			$bookingchanges = $bookingchanges->where('booking_id', 'LIKE', '%'.$request->get('booking_id').'%');
		}
		if ($request->has('is_rescheduled')) {
			$bookingchanges = $bookingchanges->where('is_rescheduled', 'LIKE', '%'.$request->get('is_rescheduled').'%');
		}
		if ($request->has('is_cancelled')) {
			$bookingchanges = $bookingchanges->where('is_cancelled', 'LIKE', '%'.$request->get('is_cancelled').'%');
		}
		if ($request->has('booking_date')) {
			$bookingchanges = $bookingchanges->where('booking_date', 'LIKE', '%'.$request->get('booking_date').'%');
		}
		if ($request->has('booking_time')) {
			$bookingchanges = $bookingchanges->where('booking_time', 'LIKE', '%'.$request->get('booking_time').'%');
		}
		if ($request->has('number_of_hours')) {
			$bookingchanges = $bookingchanges->where('number_of_hours', 'LIKE', '%'.$request->get('number_of_hours').'%');
		}
		if ($request->has('agreed_service_amount')) {
			$bookingchanges = $bookingchanges->where('agreed_service_amount', 'LIKE', '%'.$request->get('agreed_service_amount').'%');
		}
		if ($request->has('comments')) {
			$bookingchanges = $bookingchanges->where('comments', 'LIKE', '%'.$request->get('comments').'%');
		}
		if ($request->has('changed_by_user')) {
			$bookingchanges = $bookingchanges->where('changed_by_user', 'LIKE', '%'.$request->get('changed_by_user').'%');
		}
        $bookingchanges = $bookingchanges->paginate(20);
        return (new BookingchangeCollection($bookingchanges));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(BookingchangeRequest $request, Bookingchange $bookingchange)
    {
        $bookingchange = Bookingchange::firstOrNew(['id' => $request->get('id')]);
        $bookingchange->id = $request->get('id');
		$bookingchange->booking_changes_uuid = $request->get('booking_changes_uuid');
		$bookingchange->booking_id = $request->get('booking_id');
		$bookingchange->is_rescheduled = $request->get('is_rescheduled');
		$bookingchange->is_cancelled = $request->get('is_cancelled');
		$bookingchange->booking_date = $request->get('booking_date');
		$bookingchange->booking_time = $request->get('booking_time');
		$bookingchange->number_of_hours = $request->get('number_of_hours');
		$bookingchange->agreed_service_amount = $request->get('agreed_service_amount');
		$bookingchange->comments = $request->get('comments');
		$bookingchange->changed_by_user = $request->get('changed_by_user');

        $bookingchange->save();

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
        $bookingchange = Bookingchange::find($request->get('id'));
        $bookingchange->delete();
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
        $bookingchange = Bookingchange::withTrashed()->find($request->get('id'));
        $bookingchange->restore();
        return response()->json(['no_content' => true], 200);
    }
}
