<?php

namespace App\Http\Controllers\Backend\API;

use App\Useraddress;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\UseraddressRequest;
use App\Http\Resources\UseraddressCollection;
use App\Http\Resources\Useraddress as UseraddressResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use Hash;
use DB;

class UseraddressController extends Controller
{
    public function get(Request $request,$users_uuid)
    {
        $useraddresses = Useraddress::query();
        
        if ($users_uuid) {
            $useraddresses = $useraddresses->where('user_uuid', 'LIKE', '%'.$users_uuid);
        }
        if($request->has('type')) {
            $useraddresses = $useraddresses->where('type', 'LIKE', '%'.$request->get('type').'%');
        }
        if($request->has('address_line1')) {
            $useraddresses = $useraddresses->where('address_line1', 'LIKE', '%'.$request->get('address_line1').'%');
        }
        if($request->has('address_line2')) {
            $useraddresses = $useraddresses->where('address_line2', 'LIKE', '%'.$request->get('address_line2').'%');
        }
        if($request->has('subrub')){
            $useraddresses = $useraddresses->where('subrub', 'LIKE', '%'.$request->get('subrub').'%');
        }
        if ($request->has('state')){
            $useraddresses = $useraddresses->where('state', 'LIKE', '%'.$request->get('state').'%');
        }
        if ($request->has('postcode')){
            $useraddresses = $useraddresses->where('postcode', 'LIKE', '%'.$request->get('postcode').'%');
        }
        if ($request->has('country')){
            $useraddresses = $useraddresses->where('country', 'LIKE', '%'.$request->get('country').'%');
        }
        $useraddresses = $useraddresses->paginate(20);
        return (new UseraddressCollection($useraddresses));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $useraddresses = Useraddress::query();
        
		if ($request->has('user_id')) {
			$useraddresses = $useraddresses->where('user_id', 'LIKE', '%'.$request->get('user_id').'%');
		}
		if ($request->has('type')) {
			$useraddresses = $useraddresses->where('type', 'LIKE', '%'.$request->get('type').'%');
		}
		if ($request->has('address_line1')) {
			$useraddresses = $useraddresses->where('address_line1', 'LIKE', '%'.$request->get('address_line1').'%');
		}
		if ($request->has('address_line2')) {
			$useraddresses = $useraddresses->where('address_line2', 'LIKE', '%'.$request->get('address_line2').'%');
		}
		if ($request->has('subrub')) {
			$useraddresses = $useraddresses->where('subrub', 'LIKE', '%'.$request->get('subrub').'%');
		}
		if ($request->has('state')) {
			$useraddresses = $useraddresses->where('state', 'LIKE', '%'.$request->get('state').'%');
		}
		if ($request->has('postcode')) {
			$useraddresses = $useraddresses->where('postcode', 'LIKE', '%'.$request->get('postcode').'%');
		}
		if ($request->has('country')) {
			$useraddresses = $useraddresses->where('country', 'LIKE', '%'.$request->get('country').'%');
		}
        $useraddresses = $useraddresses->paginate(20);
        return (new UseraddressCollection($useraddresses));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(UseraddressRequest $request, Useraddress $useraddress)
    {
        $useraddress = Useraddress::firstOrNew(['id' => $request->get('id')]);
        $useraddress->id = $request->get('id');
		$useraddress->user_address_uuid = $request->get('user_address_uuid');
		$useraddress->user_id = $request->get('user_id');
		$useraddress->type = $request->get('type');
		$useraddress->address_line1 = $request->get('address_line1');
		$useraddress->address_line2 = $request->get('address_line2');
		$useraddress->subrub = $request->get('subrub');
		$useraddress->state = $request->get('state');
		$useraddress->postcode = $request->get('postcode');
		$useraddress->country = $request->get('country');

        $useraddress->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }

    //for add address
    public function addaddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_label'=>'nullable|string',
            'address_line1' => 'required',
            'suburb' => 'required',
            'state' => 'required',
            'postcode' => 'required|numeric'
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }
        $user = Auth::user();
        $user_id = $user->id;
        $Useraddress = Useraddress::firstOrNew(['id' => $request->get('id')]);
        $Useraddress->id = $request->get('id');
        $Useraddress->address_label = $request->get('address_label');
        $Useraddress->user_id = $user_id;
        $Useraddress->type = 'home';//$request->get('type');
        $Useraddress->address_line1 = $request->get('address_line1');
        if($request->has('address_line2') && $request->get('address_line2')!=''){
            $Useraddress->address_line2 = $request->get('address_line2');
        }
        $Useraddress->suburb = $request->get('suburb');
        $Useraddress->state = $request->get('state');
        $Useraddress->postcode = $request->get('postcode');
        $Useraddress->save();
        $id = $Useraddress->id;
      
        return response()->json(['saved' => true,'id'=>$id], 200);
        
    }

    //for update address by uuid
    public function editaddress(Request $request, $uuid)
    {

        $validator = Validator::make($request->all(), [
            'address_label'=>'nullable|string',
            'address_line1' => 'required',
            'suburb' => 'required',
            'state' => 'required',
            'postcode' => 'required|numeric'
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

        

        $user = Auth::user();
        $user_id = $user->id;
       
            $Useraddress = Useraddress::firstOrNew(['id' => $uuid]);
            // $Useraddress->type = $request->get('type');
            $Useraddress->address_label = $request->get('address_label');
            $Useraddress->address_line1 = $request->get('address_line1');
            if($request->has('address_line2') && $request->get('address_line2')!=''){
                $Useraddress->address_line2 = $request->get('address_line2');
            }
            $Useraddress->suburb = $request->get('suburb');
            $Useraddress->state = $request->get('state');
            $Useraddress->postcode = $request->get('postcode');
            $Useraddress->save();
            
            $responseCode = $request->get('id') ? 200 : 201;
            return response()->json(['saved' => $Useraddress], $responseCode);
        // }
    }

    //for get address
    public function getaddress(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        // print_r($user_id);exit;

        $useraddresses = Useraddress::query();
      	if ($user_id){
			$useraddresses = $useraddresses->where('user_id', '=', $user_id)->orderBy('id','desc');
		}
      
        $address =  $useraddresses->get();
        return response()->json(['data'=>$address]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }
        $useraddress = Useraddress::find($request->get('id'));
        $useraddress->delete();
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
        $useraddress = Useraddress::withTrashed()->find($request->get('id'));
        $useraddress->restore();
        return response()->json(['no_content' => true], 200);
    }
}
