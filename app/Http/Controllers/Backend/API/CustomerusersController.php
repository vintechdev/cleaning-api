<?php

namespace App\Http\Controllers\Backend\API;

use Illuminate\Http\Request;
use App\Customeruser;
use App\Http\Requests\Backend\CustomeruserRequest;
use App\Http\Resources\CustomeruserCollection;
use App\Http\Resources\Customeruser as CustomeruserResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
// for change password
use Auth;
use Hash;
use DB;
use App\Userreview;
class CustomerusersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(Request $request)
    {
        $Customerusers = Customeruser::query();
        
        if ($request->has('first_name')) {
            $Customerusers = $Customerusers->where('first_name', 'LIKE', '%'.$request->get('first_name').'%');
        }
        if ($request->has('uuid')) {
            $Customerusers = $Customerusers->where('uuid', 'LIKE', '%'.$request->get('uuid').'%');
        }
        if ($request->has('email')) {
            $Customerusers = $Customerusers->where('email', 'LIKE', '%'.$request->get('email').'%');
        }
        if ($request->has('password')) {
            $Customerusers = $Customerusers->where('password', 'LIKE', '%'.$request->get('password').'%');
        }
        if ($request->has('is_enduser')) {
            $Customerusers = $Customerusers->where('is_enduser', 'LIKE', '%'.$request->get('is_enduser').'%');
        }
        if ($request->has('is_provider')) {
            $Customerusers = $Customerusers->where('is_provider', 'LIKE', '%'.$request->get('is_provider').'%');
        }
        if ($request->has('mobile_number')) {
            $Customerusers = $Customerusers->where('mobile_number', 'LIKE', '%'.$request->get('mobile_number').'%');
        }
        if ($request->has('social_login')) {
            $Customerusers = $Customerusers->where('social_login', 'LIKE', '%'.$request->get('social_login').'%');
        }
        if ($request->has('remember_token')) {
            $Customerusers = $Customerusers->where('remember_token', 'LIKE', '%'.$request->get('remember_token').'%');
        }
        if ($request->has('status')) {
            $Customerusers = $Customerusers->where('status', 'LIKE', '%'.$request->get('status').'%');
        }
        if ($request->has('fcm_token')) {
            $Customerusers = $Customerusers->where('fcm_token', 'LIKE', '%'.$request->get('fcm_token').'%');
        }
        if ($request->has('id')) {
            $Customerusers = $Customerusers->where('id', '=', $request->get('id'));
        }
        if ($request->has('user_uuid')) {
            $Customerusers = $Customerusers->where('user_uuid', 'LIKE', '%'.$request->get('user_uuid').'%');
        }
        if ($request->has('email')) {
            $Customerusers = $Customerusers->where('email', 'LIKE', '%'.$request->get('email').'%');
        }
        if ($request->has('is_enduser')) {
            $Customerusers = $Customerusers->where('is_enduser', 'LIKE', '%'.$request->get('is_enduser').'%');
        }
        if ($request->has('is_provider')) {
            $Customerusers = $Customerusers->where('is_provider', 'LIKE', '%'.$request->get('is_provider').'%');
        }
        if ($request->has('date_of_birth')) {
            $Customerusers = $Customerusers->where('date_of_birth', 'LIKE', '%'.$request->get('date_of_birth').'%');
        }
        if ($request->has('mobile_number')) {
            $Customerusers = $Customerusers->where('mobile_number', 'LIKE', '%'.$request->get('mobile_number').'%');
        }
        if ($request->has('abn')) {
            $Customerusers = $Customerusers->where('abn', 'LIKE', '%'.$request->get('abn').'%');
        }
        if ($request->has('description')) {
            $Customerusers = $Customerusers->where('description', 'LIKE', '%'.$request->get('description').'%');
        }
        $Customerusers = $Customerusers->paginate(20);
        return (new CustomeruserCollection($Customerusers));
    }
    public function getallprovider(Request $request)
    {

     
    //    $sub = DB::table('user_reviews')->select('user_review_for',DB::raw('AVG(rating) as ratings_average'))->groupBy('user_reviews.user_review_for');
       



        $users = Customeruser::join('role_user', 'users.id', '=', 'role_user.user_id');
           $users->leftJoin( DB::raw("(SELECT AVG(user_reviews.rating) as avgrate, user_reviews.user_review_for FROM `user_reviews`  group by user_reviews.user_review_for) as p "), 'p.user_review_for', '=', 'users.id');
           // $query =  $users->fromSub($subQuery, 'subquery');
            
           $users->leftJoin( DB::raw("(SELECT count(provider_user_id) as completed_jobs, booking_request_providers.provider_user_id FROM `booking_request_providers` inner join bookings on(bookings.id=booking_request_providers.booking_id) where booking_request_providers.status='accepted' and bookings.booking_status_id=4 group by booking_request_providers.provider_user_id) as j"), 'j.provider_user_id', '=', 'users.id');

        //   $users->leftJoin( DB::raw("(SELECT pm.amount,pm.type,sr.is_default_service,pm.provider_id from provider_service_maps pm join services sr on(pm.service_id=sr.id) where sr.is_default_service=1 and sr.deleted_at is null and pm.deleted_at is null) as r"), 'r.provider_id', '=', 'users.id');

            if ($request->has('servicecategory') || $request->has('serviceid')){
                $users
                    ->join('provider_service_maps', 'users.id', '=', 'provider_service_maps.provider_id')
                    ->join('services', 'provider_service_maps.service_id', '=', 'services.id')
                    ->join('service_categories', 'services.category_id', '=', 'service_categories.id');
            }

        if ($request->has('postcode')){
            $users
            ->join('provider_postcode_maps', 'users.id', '=', 'provider_postcode_maps.provider_id')
            ->join('postcodes', 'provider_postcode_maps.postcode_id', '=', 'postcodes.id');

        }
        if ($request->has('day') || ($request->has('start_time') && $request->has('end_time'))){
            $users
                ->join('provider_working_hours', 'users.id', '=', 'provider_working_hours.provider_id');
        }
       /*  $users->leftJoin('user_reviews', function( $join){
            $join->on('user_reviews.user_review_for', 'users.id');
        }); */
        $users
            ->select(['users.*','p.avgrate','j.completed_jobs'])//,'r.*'
            ->where('role_id', 2);

            if ($request->has('providertype')){
                $users->where(
                    'users.providertype',
                    $request->has('providertype')
                );
            }

        if ($request->has('servicecategory')){
            $users->where('service_categories.id', $request->get('servicecategory'));
        }
     
        if ($request->has('postcode')) {
            $users->where('postcodes.postcode', $request->get('postcode'));
        }
        if ($request->has('day') || ($request->has('start_time') )) {//&& $request->has('end_time')
            if ($request->get('day')) {
                $users->where('provider_working_hours.working_days', 'LIKE', '%' . $request->get('day') . '%');
            }

            if ($request->has('start_time') && $request->has('end_time')){
                $users->whereTime('provider_working_hours.start_time', '<=', $request->get('start_time'));
                   // ->whereTime('provider_working_hours.end_time', '>=', $request->get('end_time'));
            }
        }
       
        if ($request->has('serviceid')){
            $servicearr =  explode(',',$request->get('serviceid'));
            $users->whereIn('provider_service_maps.service_id',explode(',',$request->get('serviceid')));
            $users->groupBy('users.id','p.avgrate')->havingRaw("count(provider_service_maps.provider_id)=".count( $servicearr));
        }

     
    //   $arr = $users->select(['users.*',DB::raw('AVG(user_reviews.rating) as ratings_average' )])->get();
    $arr = $users->where('users.providertype','agency')->get();
 // dd($arr);
      
       return response()->json(['data' => $users->get()]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(CustomeruserRequest $request, Customeruser $Customeruser)
    {
        $Customeruser = Customeruser::firstOrNew(['id' => $request->get('id')]);
        $Customeruser->id = $request->get('id');
        $Customeruser->user_uuid = $request->get('user_uuid');
        $Customeruser->first_name = $request->get('first_name');
        $Customeruser->email = $request->get('email');
        $Customeruser->password = bcrypt($request->get('password'));
        $Customeruser->is_enduser = $request->get('is_enduser');
        $Customeruser->is_provider = $request->get('is_provider');
        $Customeruser->mobile_number = $request->get('mobile_number');
        $Customeruser->social_login = $request->get('social_login');
        $Customeruser->remember_token = $request->get('remember_token');
        // $Customeruser->status = $request->get('status');
        // $Customeruser->fcm_token = $request->get('fcm_token');
        
        $Customeruser->save();
    
        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }

    // for change password
    public function change_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required',
            'repeat_password' => 'required|same:new_password'
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

        // echo "change_password";exit;
        $user = Auth::user();
        $user_id = $user->id;
        $user_password = $user->password;
        // $user = auth('api')->user()->id;
        // print_r($user_id);exit;
        $Customeruser = Customeruser::firstOrNew(['id' => $user_id]);
        // $Customeruser->uuid = $users_uuid;
        
        $current_password = $request->get('current_password');
        $responseCode =  Hash::check($current_password, $user_password);
        // print_r($responseCode);exit;
        if($responseCode > 0){

            $new_password = $request->get('new_password');
            $repeat_password = $request->get('repeat_password');

            if($new_password == $repeat_password){
                // echo "same";exit;
                // $Customerusers = Customeruser::query();
                // $Customerusers = $Customerusers->where('uuid', 'LIKE', '%'.$users_uuid);
                $Customeruser->password = bcrypt($new_password);
                $Customeruser->save();

                $responseCode = $request->get('id') ? 200 : 201;
                return response()->json(['saved' => true], $responseCode);
            } else{
                // echo "not same";exit;
                $responseCode = $request->get('id') ? 200 : 201;
                return response()->json(['error' => "New Password and Repeat Password do not match"], $responseCode);
            }   
        } else{
            // echo "Enter correct Current Password";
            $responseCode = $request->get('id') ? 200 : 201;
            return response()->json(['error' => "Please enter correct Current Password or Invalid Authorization"], $responseCode);
        }
        

        // $responseCode = $request->get('id') ? 200 : 201;
        // return response()->json(['saved' => true], $responseCode);
    }

    //for profile update
    public function profile_update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'mobile_number' => 'required|min:10|max:10',
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

        $user = Auth::user();
        $user_id = $user->id;
        // print_r($user_id);exit;

        $Customeruser = Customeruser::firstOrNew(['id' => $user_id]);
        $Customeruser->first_name = $request->get('first_name');
        $Customeruser->last_name = $request->get('last_name');
        $Customeruser->email = $request->get('email');
        $Customeruser->profilepic = $request->get('profilepic');
        $Customeruser->mobile_number = $request->get('mobile_number');
        $Customeruser->save();
        
        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => $Customeruser], $responseCode);
    }

    public function profile_view(Request $request)
    {
        
        
        

        $user = Auth::user();
        $user_id = $user->id;
        // print_r($user_id);exit;

        $Customeruser = Customeruser::firstOrNew(['id' => $user_id]);
        
        
        return $Customeruser;
    }
    public function address_view(Request $request)
    {

        $user = Auth::user();
        $user_id = $user->id;
        // print_r($user_id);exit;

        $Customeraddress = Useraddress::firstOrNew(['user_id' => $user_id]);
        
        
        return $Customeraddress;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $Customeruser = Customeruser::find($request->get('id'));
        $Customeruser->delete();
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
        $Customeruser = Customeruser::withTrashed()->find($request->get('id'));
        $Customeruser->restore();
        return response()->json(['no_content' => true], 200);
    }
}
