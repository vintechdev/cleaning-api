<?php

namespace App\Http\Controllers\Backend\API;
use App\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Customeruser;
use App\Http\Requests\Backend\CustomeruserRequest;
use App\Http\Resources\CustomeruserCollection;
use App\Http\Resources\Customeruser as CustomeruserResource;
use App\Http\Controllers\Controller;
use App\Repository\UserBadgeReviewRepository;
use Illuminate\Support\Facades\Validator;
// for change password

use Hash;
use DB;
use App\Userreview;
use Session;
use File;
use Config;
use Illuminate\Support\Facades\Auth;
use App\Repository\Eloquent\ProfileRepository;

class CustomerusersController extends Controller
{
    protected $profileRepository;
    protected $userbage;

    public function __construct(ProfileRepository $profileRepository,UserBadgeReviewRepository $userbage){
        $this->profileRepository = new ProfileRepository();
        $this->userbage = $userbage;
    }

    public function getBadges()
    {
        # code...
        $arr = $this->userbage->getBadgeDetails(Auth::user()->id);
        return response()->json(['data'=>$arr],200);
    }
    
    public function profilepicture(Request $request){
         $rules = array(
           'file_content'=>'required|string'
        ); 

     
        $params = $request->all();
        $validator = Validator::make($params, $rules);
        if ($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }else{

            $image = $request->input('file_content'); 
            $user_id = $request->get('user_id') && $request->user()->isAdminScope() ? $request->get('user_id')  : Auth::user()->id;
            

            $Customeruser = Customeruser::firstOrNew(['id' => $user_id]);
            if($image!=''){
                $type = $request->file_type;
               
                $image = str_replace('data:'.$type.';base64,', '', $image);
              
                $ext = str_replace('image/','',$type);
                $image = str_replace(' ', '+', $image);
                
                $imageName = time().'.'. $ext ;
                $destinationPath = \Config::get('const.PROFILE_PATH'); //public_path().'/images/upload/profile/';
          //    echo $imageName;exit;
                \File::put( $destinationPath . $imageName, base64_decode($image));
                $Customeruser->profilepic = $imageName;
                $Customeruser->save();
                return response()->json(['message' => 'Profile Image has been updated successfully!!'], 200);
            }else{
                return response()->json(['message' => 'Profile picture is not found. Try again!!'], 401);
            }
            
        }
    }


    public function CheckProfileCompleted(Request $request)
    {
        $res = $this->profileRepository->CheckProfileCompleted();

        if($res['working_hour']>0 && $res['provider_service']>0 && $res['postcode']>0 && $res['payment']>0){
            $arr = ['success'=>true,'data'=>$res];
        }else{
            if($res['working_hour']==0 && $res['provider_service']==0 && $res['postcode']==0 && $res['payment']==0){
                $arr = ['data'=>$res,'success'=>false,'message'=>'Please fill details to complete the profile.'];
            }else if($res['working_hour']==0 && $res['provider_service']>0 && $res['postcode']>0 && $res['payment']>0){
                $arr = ['data'=>$res,'success'=>false,'message'=>'Please fill working hours details to complete profile.'];
            }else if($res['working_hour']>0 && $res['provider_service']==0 && $res['postcode']>0 && $res['payment']>0){
                $arr = ['data'=>$res,'success'=>false,'message'=>'Please fill service details to complete profile.'];
            }else if($res['working_hour']>0 && $res['provider_service']>0 && $res['postcode']==0 && $res['payment']>0){
                $arr = ['data'=>$res,'success'=>false,'message'=>'Please fill postcode details to complete profile.'];
            }else if($res['working_hour']>0 && $res['provider_service']>0 && $res['postcode']>0 && $res['payment']==0){
                $arr = ['data'=>$res,'success'=>false,'message'=>'Please fill payment details to complete profile.'];
            }else{
                $arr = ['data'=>$res,'success'=>false,'message'=>'Please fill details to complete the profile.'];
            }
        }
        return response()->json($arr);
    }

    public function checkproviderstripeverified()
    {
        $res = $this->profileRepository->CheckProviderStripeVarified();
        return response()->json(['varified'=>(($res>0)?1:0)],200);
    }


    
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

        if ($request->has('role')) {
            $Customerusers = $Customerusers->whereExists(
                function($query) use ($request) {  
                 $query->from('role_user')
                       ->join('roles', 'role_user.role_id', '=', 'roles.id')
                       ->where('roles.name', 'like', \DB::raw("'". $request->get('role') ."'"))
                       ->where('role_user.user_id', '=', \DB::raw('users.id'));
                });
        }


        $Customerusers = $Customerusers->paginate(10000);
        return (new CustomeruserCollection($Customerusers));
    }
    public function getallprovider(Request $request){
    
        $rules = array(
            'postcode' => 'required|numeric',
            'serviceid'=>'string',
            'servicecategory'=>'required|numeric',
            'start_time'=>'nullable|string',
            'end_time'=>'nullable|string',
            'day'=>'nullable|string'
        );

     
        $params = $request->all();
        $validator = Validator::make($params, $rules);
        if ($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 400);
        }else{

            $users = Customeruser::join('role_user', 'users.id', '=', 'role_user.user_id');
            $users->leftJoin( DB::raw("(SELECT AVG(user_reviews.rating) as avgrate, user_reviews.user_review_for FROM `user_reviews`  group by user_reviews.user_review_for) as p "), 'p.user_review_for', '=', 'users.id');
            // $query =  $users->fromSub($subQuery, 'subquery');
            
            $users->leftJoin( DB::raw("(SELECT count(provider_user_id) as completed_jobs, booking_request_providers.provider_user_id FROM `booking_request_providers` inner join bookings on(bookings.id=booking_request_providers.booking_id) where booking_request_providers.status='accepted' and bookings.booking_status_id=4 group by booking_request_providers.provider_user_id) as j"), 'j.provider_user_id', '=', 'users.id');

            $users
                ->join('provider_service_maps', 'users.id', '=', 'provider_service_maps.provider_id')
                ->join('services', 'provider_service_maps.service_id', '=', 'services.id')
                ->join('service_categories', 'services.category_id', '=', 'service_categories.id');

            if ($request->has('postcode')){

                $users->Join(DB::raw("( select DISTINCT provider_id from `provider_postcode_maps`
                INNER JOIN `postcodes` 
                        ON `provider_postcode_maps`.`postcode_id` = `postcodes`.`id` 
                        where postcode = ".$request->get('postcode')." and provider_postcode_maps.deleted_at is null
                ) as prv"), 'prv.provider_id', '=', 'users.id');
             

            //    $users->join('provider_postcode_maps', 'users.id', '=', 'provider_postcode_maps.provider_id')
              //  ->join('postcodes', 'provider_postcode_maps.postcode_id', '=', 'postcodes.id');

            }
            if ($request->has('day') || ($request->has('start_time') && $request->has('end_time'))){
                $users
                    ->join('provider_working_hours', 'users.id', '=', 'provider_working_hours.provider_id');
            }
           /*  $users->leftJoin('user_reviews', function( $join){
                $join->on('user_reviews.user_review_for', 'users.id');
            });  */
            $users->select(['users.*','p.avgrate','j.completed_jobs',DB::raw('case when services.allow_price_override=1 AND provider_service_maps.amount is not null THEN provider_service_maps.amount ELSE services.service_cost END as amount'),'services.unit_type', 'provider_service_maps.type', 'services.is_default_service', 'provider_service_maps.provider_id'])->where('role_id', 2);

            $users->where('users.status', 'active');
            
            if ($request->has('providertype')){
                    $users->where('users.providertype',$request->has('providertype'));
            }
            if ($request->has('servicecategory')){
                $users->where('service_categories.id', $request->get('servicecategory'));
            }
            
            if ($request->has('day') || ($request->has('start_time') )) {//&& $request->has('end_time')
                if ($request->get('day')) {
                    $users->where('provider_working_hours.working_days', 'LIKE', '%' . $request->get('day') . '%');
                }
                if ($request->has('start_time')){
                    $users->whereTime('provider_working_hours.start_time', '<=', $request->get('start_time'));
                    // ->whereTime('provider_working_hours.end_time', '>=', $request->get('end_time'));
                }
            }
        
            if($request->has('serviceid')){
                $originalservicearr =  explode(',',$request->get('serviceid'));

                // Ignore non-default service here as we want to increase the list
                // of providers at this point
                $servicearr = [];
                foreach ($originalservicearr as $serviceId) {
                    /** @var Service $service */
                    $service = Service::find($serviceId);
                    if ($service->isDefaultService()) {
                        $servicearr[] = $serviceId;
                    }
                }
             
                $users->whereIn('provider_service_maps.service_id', $servicearr);
                $users->groupBy('users.id','p.avgrate','provider_service_maps.amount','provider_service_maps.type','services.is_default_service')->havingRaw("count(provider_service_maps.provider_id)=".count( $servicearr));
            }

            if($request->has('pricerange') && $request->get('pricerange')>0){
                $users->where('provider_service_maps.amount','<=',$request->get('pricerange'));
            }
            if($request->has('cleaningrange') && $request->get('cleaningrange')>0){
                $users->where(DB::raw('IFNULL(j.completed_jobs,0)'),'>=',$request->get('cleaningrange'));
            }
             if($request->has('rating') && $request->get('rating')>0){
                $users->where(DB::raw('IFNULL(p.avgrate,0)'),'>=',$request->get('rating'));
            } 
            if($request->has('sorting')){
                if($request->get('sorting')=='new'){
                    $column = 'users.created_at';
                    $dir = 'desc';
                }else if($request->get('sorting')=='pasc'){
                    $column = 'provider_service_maps.amount';
                    $dir = 'asc';
                }else if($request->get('sorting')=='pdesc'){
                    $column = 'provider_service_maps.amount';
                    $dir = 'desc';
                }else{
                    $column = 'j.completed_jobs';
                    $dir = 'desc';
                }
              //  echo $column.'--'.$dir;die;
                $users->orderBy($column,$dir);
            }
           // echo $users->toSql();exit;
            $agency = clone $users;
            $agencyprice = clone $users;
            $users->where('providertype','freelancer');
            $freelancer = $users->get()->toArray();
          //  dd($freelancer);
            $agency = $agency->where('providertype','agency')->pluck('id')->toArray();//->toArray();
           // dd($agency);
           $highagencyprice = 0;
            if(count($agency)>0){
                
           
                 $arr = $agencyprice->where('providertype','agency')->orderBy('provider_service_maps.amount','desc')->limit(1)->pluck('amount')->toArray();
                 $highagencyprice = $arr[0];
            }
            
            return response()->json(['data' =>$freelancer,'agency'=>count($agency),'agencyids'=>$agency,'highagencyprice'=>$highagencyprice],200);
    }
      
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
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password'
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

        //$user_id = $user->id;
        $user_id = $this->getUserIdByLoggedUserIdOrRequest($request);
        $user = Customeruser::query()->find($user_id);
        $user_password = $user->password;
       
        $Customeruser = Customeruser::firstOrNew(['id' => $user_id]);
        
        $current_password = $request->get('old_password');
        $responseCode =  Hash::check($current_password, $user_password);
        // print_r($responseCode);exit;
        if($responseCode > 0){
            $new_password = $request->get('new_password');
            $repeat_password = $request->get('confirm_password');

            if($new_password == $repeat_password){
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
    }

    //for profile update
    public function profile_update(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'mobile_number' => 'required|min:9|max:12',
            'user_id' => 'nullable|integer|exists:users,id',
            'status' => 'nullable|string|'
        ]);

        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

        $user_id = $this->getUserIdByLoggedUserIdOrRequest($request);   
        $count = Customeruser::where('email', $request->get('email'))
        ->where('id','!=', $user_id)->count();
        
        $image = $request->input('file_content'); // your base64 encoded

        if($count==0){
            $Customeruser = Customeruser::firstOrNew(['id' => $user_id]);
            $Customeruser->first_name = $request->get('first_name');
            $Customeruser->last_name = $request->get('last_name');
            $Customeruser->email = $request->get('email');
          
            $Customeruser->mobile_number = $request->get('mobile_number');

            if ($request->user()->isAdminScope() && $request->get('status')) {
                $Customeruser->status = $request->get('status');   
            }    

            $Customeruser->save();
            $message ='Profile update successfully.';

        }else{
            $message ='Email already exists.';
            $Customeruser = 'exist';
        }
        
        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => $Customeruser,'message'=>$message], $responseCode);
    }

    public function profile_view(Request $request)
    {
        $userId = $this->getUserIdByLoggedUserIdOrRequest($request);       
        $Customeruser = Customeruser::firstOrNew(['id' => $userId]);
        
        return $Customeruser;
    }


    private function getUserIdByLoggedUserIdOrRequest($request)
    {
        return $request->get('user_id') && $request->user()->isAdminScope()
        ?  $request->get('user_id') : Auth::user()->id;
        
    }

    public function address_view(Request $request)
    {
        $userId = $this->getUserIdByLoggedUserIdOrRequest($request);       
        $Customeraddress = Useraddress::firstOrNew(['user_id' => $userId]);
        
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

    /**
     * Restore the specified resource to storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getReviews(Request $request)
    {
        $reviews = [];
        if ($request->get('role') == 'provider') {
            $reviews = $this->userbage->getReviewDetails($request->get('user_id'));
        } else {
            $reviews = $this->userbage->getReviewsByUser($request->get('user_id'));
        }

        return response()->json(['data' => $reviews], 200);
    }
}
