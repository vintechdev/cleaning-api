<?php

namespace App\Http\Controllers\Backend\API;

use App\Customermetadata;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\CustomermetadatumRequest;
use App\Http\Resources\CustomermetadatumCollection;
use App\Http\Resources\Customermetadatum as CustomermetadatumResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use Hash;
use DB;

class CustomermetadataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customermetadata = Customermetadata::query();
        
		if ($request->has('enduser_metadata_uuid')) {
			$customermetadata = $customermetadata->where('enduser_metadata_uuid', 'LIKE', '%'.$request->get('enduser_metadata_uuid').'%');
		}
		if ($request->has('user_id')) {
			$customermetadata = $customermetadata->where('user_id', 'LIKE', '%'.$request->get('user_id').'%');
		}
		if ($request->has('status')) {
			$customermetadata = $customermetadata->where('status', 'LIKE', '%'.$request->get('status').'%');
		}
		if ($request->has('card_number')) {
			$customermetadata = $customermetadata->where('card_number', 'LIKE', '%'.$request->get('card_number').'%');
		}
		if ($request->has('card_name')) {
			$customermetadata = $customermetadata->where('card_name', 'LIKE', '%'.$request->get('card_name').'%');
		}
		if ($request->has('user_card_type')) {
			$customermetadata = $customermetadata->where('user_card_type', 'LIKE', '%'.$request->get('user_card_type').'%');
		}
		if ($request->has('card_cvv')) {
			$customermetadata = $customermetadata->where('card_cvv', 'LIKE', '%'.$request->get('card_cvv').'%');
		}
		if ($request->has('user_card_expiry')) {
			$customermetadata = $customermetadata->where('user_card_expiry', 'LIKE', '%'.$request->get('user_card_expiry').'%');
		}
		if ($request->has('user_card_last_four')) {
			$customermetadata = $customermetadata->where('user_card_last_four', 'LIKE', '%'.$request->get('user_card_last_four').'%');
		}
		if ($request->has('user_stripe_customer_id')) {
			$customermetadata = $customermetadata->where('user_stripe_customer_id', 'LIKE', '%'.$request->get('user_stripe_customer_id').'%');
		}
        $customermetadata = $customermetadata->paginate(20);
        return (new CustomermetadatumCollection($customermetadata));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(CustomermetadatumRequest $request, Customermetadata $customermetadatum)
    {
        $customermetadatum = Customermetadata::firstOrNew(['id' => $request->get('id')]);
        $customermetadatum->id = $request->get('id');
		$customermetadatum->enduser_metadata_uuid = $request->get('enduser_metadata_uuid');
		$customermetadatum->user_id = $request->get('user_id');
		$customermetadatum->status = $request->get('status');
		$customermetadatum->card_number = $request->get('card_number');
		$customermetadatum->card_name = $request->get('card_name');
		$customermetadatum->user_card_type = $request->get('user_card_type');
		$customermetadatum->card_cvv = $request->get('card_cvv');
		$customermetadatum->user_card_expiry = $request->get('user_card_expiry');
		$customermetadatum->user_card_last_four = $request->get('user_card_last_four');
		$customermetadatum->user_stripe_customer_id = $request->get('user_stripe_customer_id');

        $customermetadatum->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }

    //for card details update
    public function card_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_number' => 'required|numeric|digits_between:16,16',
            'card_name' => 'required',
            'card_cvv' => 'required|numeric|digits_between:3,3',
            'user_card_expiry' => 'required',
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

        $user = Auth::user();
        $user_id = $user->id;
        
        $ccNum = $request->get('card_number');
        $last4Digits = preg_replace( "#(.*?)(\d{4})$#", "$2", $ccNum);
        // print_r($last4Digits);exit;

        $Customermetadata = Customermetadata::firstOrNew(['user_id' => $user_id]);
        $Customermetadata->card_number = $request->get('card_number');
        $Customermetadata->card_name = $request->get('card_name');
        $Customermetadata->expiry_month = $request->get('expiry_month');
        $Customermetadata->expiry_year = $request->get('expiry_year');
        $Customermetadata->card_cvv = $request->get('card_cvv');
        $Customermetadata->user_card_last_four = $last4Digits;
        $Customermetadata->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => $Customermetadata], $responseCode);
    }
   public function card_dataadd(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'card_number' => 'required|numeric|digits_between:16,16',
            'card_name' => 'required',
            'card_cvv' => 'required|numeric|digits_between:3,3',
            'user_card_expiry' => 'required',
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

        $user = Auth::user();
        $user_id = $user->id;
        
        $ccNum = $request->get('card_number');
        $last4Digits = preg_replace( "#(.*?)(\d{4})$#", "$2", $ccNum);
        

        $Customermetadata = Customermetadata::firstOrNew(['id' => $request->get('id')]);

        $Customermetadata->card_number = $request->get('card_number');
        $Customermetadata->user_id = $user_id;
        $Customermetadata->status = 'active';
        $Customermetadata->card_name = $request->get('card_name');
        $Customermetadata->expiry_month = $request->get('expiry_month');
        $Customermetadata->expiry_year = $request->get('expiry_year');
        $Customermetadata->card_cvv = $request->get('card_cvv');
        $Customermetadata->user_card_expiry = $request->get('user_card_expiry');
        $Customermetadata->user_card_last_four = $last4Digits;
        $Customermetadata->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => $Customermetadata], $responseCode);

    }

    //for get payment settings
    public function getpaymentsettings(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        // print_r($user_id);exit;

        $data = DB::table('customer_metadata')
            ->select('*')
            ->where('user_id', $user_id)
            ->limit(1)
            ->orderBy('id', 'DESC')
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
        $customermetadatum = Customermetadata::find($request->get('id'));
        $customermetadatum->delete();
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
        $customermetadatum = Customermetadata::withTrashed()->find($request->get('id'));
        $customermetadatum->restore();
        return response()->json(['no_content' => true], 200);
    }
}
