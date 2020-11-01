<?php

namespace App\Http\Controllers\Backend\API;

use App\Payment;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\PaymentRequest;
use App\Http\Resources\PaymentCollection;
use App\Http\Resources\Payment as PaymentResource;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $payments = Payment::query();
        
		if ($request->has('id')) {
			$payments = $payments->where('id', 'LIKE', '%'.$request->get('id').'%');
		}
		if ($request->has('booking_id')) {
			$payments = $payments->where('booking_id', 'LIKE', '%'.$request->get('booking_id').'%');
		}
		if ($request->has('stripe_charge_status')) {
			$payments = $payments->where('stripe_charge_status', 'LIKE', '%'.$request->get('stripe_charge_status').'%');
		}
		if ($request->has('charge_completion_datetime')) {
			$payments = $payments->where('charge_completion_datetime', 'LIKE', '%'.$request->get('charge_completion_datetime').'%');
		}
		if ($request->has('payment_descriptor')) {
			$payments = $payments->where('payment_descriptor', 'LIKE', '%'.$request->get('payment_descriptor').'%');
		}
		if ($request->has('total_amount')) {
			$payments = $payments->where('total_amount', 'LIKE', '%'.$request->get('total_amount').'%');
		}
		if ($request->has('payout_status')) {
			$payments = $payments->where('payout_status', 'LIKE', '%'.$request->get('payout_status').'%');
		}
		if ($request->has('payout_date')) {
			$payments = $payments->where('payout_date', 'LIKE', '%'.$request->get('payout_date').'%');
		}
        $payments = $payments->paginate(20);
        return (new PaymentCollection($payments));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(PaymentRequest $request, Payment $payment)
    {
        $payment = Payment::firstOrNew(['id' => $request->get('id')]);
        $payment->id = $request->get('id');
		$payment->payment_uuid = $request->get('payment_uuid');
		$payment->booking_id = $request->get('booking_id');
		$payment->stripe_charge_status = $request->get('stripe_charge_status');
		$payment->charge_completion_datetime = $request->get('charge_completion_datetime');
		$payment->payment_descriptor = $request->get('payment_descriptor');
		$payment->total_amount = $request->get('total_amount');
		$payment->payout_status = $request->get('payout_status');
		$payment->payout_date = $request->get('payout_date');

        $payment->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }

    //for get payment history
    public function getpaymenthistory(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        // print_r($user_id);exit;

        $data = DB::table('payments')
            ->join('booking_services', 'payments.booking_id', '=', 'booking_services.booking_id')
            ->join('services', 'booking_services.service_id', '=', 'services.id')
            ->join('service_categories', 'services.category_id', '=', 'service_categories.id')
            ->select('payments.total_amount', 'payments.created_at', 'services.name as service_name', 'service_categories.name as servicecategory_name','payments.booking_id')
            ->where('user_id', $user_id)
            ->get();
        // print_r($data);exit;

        return response()->json(['data' => $data]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $payment = Payment::find($request->get('id'));
        $payment->delete();
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
        $payment = Payment::withTrashed()->find($request->get('id'));
        $payment->restore();
        return response()->json(['no_content' => true], 200);
    }
}
