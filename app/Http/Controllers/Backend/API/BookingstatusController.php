<?php

namespace App\Http\Controllers\Backend\API;

use App\Bookingstatus;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\BookingstatusRequest;
use App\Http\Resources\BookingstatusCollection;
use App\Http\Resources\Bookingstatus as BookingstatusResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use Hash;
use DB;

class BookingstatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bookingstatuses = Bookingstatus::query();
        
		if ($request->has('status')) {
			$bookingstatuses = $bookingstatuses->where('status', 'LIKE', '%'.$request->get('status').'%');
		}
		if ($request->has('description')) {
			$bookingstatuses = $bookingstatuses->where('description', 'LIKE', '%'.$request->get('description').'%');
		}
        $bookingstatuses = $bookingstatuses->paginate(20);
        return (new BookingstatusCollection($bookingstatuses));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(BookingstatusRequest $request, Bookingstatus $bookingstatus)
    {
        $bookingstatus = Bookingstatus::firstOrNew(['id' => $request->get('id')]);
        $bookingstatus->id = $request->get('id');
		$bookingstatus->booking_status_uuid = $request->get('booking_status_uuid');
		$bookingstatus->status = $request->get('status');
		$bookingstatus->description = $request->get('description');

        $bookingstatus->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }

    // for get all booking status
    public function getallbookingstatus(Request $request)
    {
        $booking_status = DB::table('booking_status')
        ->select('*')
        ->get();

        return response()->json(['data' => $booking_status]);    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $bookingstatus = Bookingstatus::find($request->get('id'));
        $bookingstatus->delete();
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
        $bookingstatus = Bookingstatus::withTrashed()->find($request->get('id'));
        $bookingstatus->restore();
        return response()->json(['no_content' => true], 200);
    }
}
