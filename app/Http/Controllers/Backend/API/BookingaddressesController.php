<?php

namespace App\Http\Controllers\Backend\API;

use App\Bookingaddress;
use Illuminate\Http\Request;
use Shahnewaz\Redprint\Traits\CanUpload;
use App\Http\Requests\Backend\BookingaddressRequest;
use App\Http\Resources\BookingaddressCollection;
use App\Http\Resources\Bookingaddress as BookingaddressResource;
use App\Http\Controllers\Controller;

class BookingaddressesController extends Controller
{
    use CanUpload;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bookingaddresses = Bookingaddress::query();
        
		if ($request->has('booking_id')) {
			$bookingaddresses = $bookingaddresses->where('booking_id', 'LIKE', '%'.$request->get('booking_id').'%');
		}
		if ($request->has('address_line1')) {
			$bookingaddresses = $bookingaddresses->where('address_line1', 'LIKE', '%'.$request->get('address_line1').'%');
		}
		if ($request->has('address_line2')) {
			$bookingaddresses = $bookingaddresses->where('address_line2', 'LIKE', '%'.$request->get('address_line2').'%');
		}
		if ($request->has('subrub')) {
			$bookingaddresses = $bookingaddresses->where('subrub', 'LIKE', '%'.$request->get('subrub').'%');
		}
		if ($request->has('state')) {
			$bookingaddresses = $bookingaddresses->where('state', 'LIKE', '%'.$request->get('state').'%');
		}
		if ($request->has('postcode')) {
			$bookingaddresses = $bookingaddresses->where('postcode', 'LIKE', '%'.$request->get('postcode').'%');
		}
		if ($request->has('country')) {
			$bookingaddresses = $bookingaddresses->where('country', 'LIKE', '%'.$request->get('country').'%');
		}
        $bookingaddresses = $bookingaddresses->paginate(20);
        return (new BookingaddressCollection($bookingaddresses));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(BookingaddressRequest $request, Bookingaddress $bookingaddress)
    {
        $bookingaddress = Bookingaddress::firstOrNew(['id' => $request->get('id')]);
        $bookingaddress->id = $request->get('id');
		$bookingaddress->booking_address_uuid = $request->get('booking_address_uuid');
		$bookingaddress->booking_id = $request->get('booking_id');
		$bookingaddress->address_line1 = $request->get('address_line1');
		$bookingaddress->address_line2 = $request->get('address_line2');
		$bookingaddress->subrub = $request->get('subrub');
		$bookingaddress->state = $request->get('state');
		$bookingaddress->postcode = $request->get('postcode');
		$bookingaddress->country = $request->get('country');

        $bookingaddress->save();

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
        $bookingaddress = Bookingaddress::find($request->get('id'));
        $bookingaddress->delete();
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
        $bookingaddress = Bookingaddress::withTrashed()->find($request->get('id'));
        $bookingaddress->restore();
        return response()->json(['no_content' => true], 200);
    }
}
