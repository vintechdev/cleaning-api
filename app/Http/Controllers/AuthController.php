<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Role;
use App\Register;
use App\RoleUser;
use App\Customermetadata;
use App\Http\Resources\LoginActivityLog;
use Route;
use DB;
use Illuminate\Support\Str;
use App\PasswordReset;
//use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Hash;
// for email verify
use Stripe;
use Validator;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Events\Verified;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;
use App\Loginactivitylogs;
use Config;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    use VerifiesEmails;
    use ThrottlesLogins;

    public function __construct()
    {
       // $this->beforeFilter('throttle:2,1', ['only' =>['login']]);
        
    }
    
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    // public function signup(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string',
    //         'email' => 'required|string|email|unique:users',
    //         'password' => 'required|string|confirmed'
    //     ]);
    //     $user = new User([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => bcrypt($request->password)
    //     ]);
    //     $user->save();
    //     return response()->json([
    //         'message' => 'Successfully created user!'
    //     ], 201);
    // }
public function UpdateToken(Request $request)
{
    $validator = $request->validate([
        'email' => 'required|email',
    ]);

    $passwordReset = PasswordReset::updateOrCreate(
        ['email' => $request->email],
        [
            'email' => $request->email,
            'token' =>Str::random(60)
         ]
    );
    if($passwordReset){
        return response()->json(['success'=>true,'token'=>$passwordReset->token],201);
    }else{

    }
    
}
     public function UserExist(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|email',
        ]);
    //    $count = User::where(['email' => $request->email])->count();
        $user = User::where('email', $request->email)->first();
        if($user){
            return response()->json(['success'=>true,'user'=>$user],201);
        }else{
            return response()->json(['error'=>'Email not found. Please try again.'],404);
        }
        # code...
    }


    public function register(Request $request)
    {
      //  dd($request->all());
         $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name'=>'required',
            'password' => 'required',
            'email'=>'required|email',
            'mobile_number' => 'required|numeric'
        ]);

        
        if ($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 400);
        }
        $count = User::where(['email' => $request->get('email')])->count();
        if($count>0){
                return response()->json(['error'=>'Email already Exists. Please try again with another Email.']);
        }else{

     
        try { 
            // $User = new Register();
            $User = User::firstOrNew(['id' => $request->get('id')]);
            $User->id = $request->get('id');
            $User->uuid = $request->get('uuid');
            $User->first_name = $request->get('first_name');
            $User->last_name = $request->get('last_name');
            $User->email = $request->get('email');
            $User->password = bcrypt($request->get('password'));
            $User->mobile_number = $request->get('mobile_number');
            $User->social_login = $request->get('social_login');
            $User->remember_token = $request->get('remember_token');
            $User->status = "email_verification";
            $User->fcm_token = $request->get('fcm_token');
            $roles = explode(',', $request->get('role'));
            // dd($roles);
            $User->save();
            $success['saved'] = true;
        } catch(\Illuminate\Database\QueryException $ex){ 
            dd($ex->getMessage()); 
            // Note any method of class PDOException can be called on $ex.
        }
        
        $lastinsertid = $User->id;


        // for email verify
        $User->sendApiEmailVerificationNotification();
        $success['message'] = 'Please confirm yourself by clicking on verify user button sent to you on your email';
        
        foreach($roles as $r){
            // print_r($r);
            $RoleUser = new RoleUser();
            $RoleUser->user_id = $lastinsertid;

            $rl = new Role();
            $rls = $rl->where('name', $r)->first();
       
            $RoleUser->role_id = $rls->id;
            $RoleUser->save();

            if($r == 'customer'){
                $options = [
                    "email" => $User->email
                ];
                $user_id = $User->id;
                //$stripeCustomer = $User->createAsStripeCustomer();
                $Customermetadata = new Customermetadata();
                
            }

            if($r == 'provider'){
                $User = User::firstOrNew(['id' => $lastinsertid]);
                $User->providertype = $request->get('provider_type');
                $User->save();
            }
        }
        
        // $email = $request->get('email');

      

        // Mail::to($email)->send(new WelcomeMail($data));
    
        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['success' => $success], $responseCode);
    }
    }
  
    /**
     * Login user and create token
     *n
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function maxAttempts(){
        //Lock out on 5th Login Attempt
        return Config::get('auth.MAX_ATTEMPT');
    }

    public function decayMinutes()
    {
        //Lock for 1 minute
        return  Config::get('auth.DECAY_TIME');
    }

protected function sendLockoutResponse(Request $request)
{
     $seconds = $this->limiter()->availableIn(
        $this->throttleKey($request)
    );
    return response()->json([
        'message' => Lang::get('auth.throttle', ['seconds' => $seconds])
    ], 423);
    /* throw ValidationException::withMessages([
        'throttle' => [Lang::get('auth.throttle', ['seconds' => $seconds])],
    ])->status(Response::HTTP_TOO_MANY_REQUESTS); */
  
}
public function logoutlog(Request $request)
{ 
    $log = array(
        'userid'=>$request->id,
        'ip'=>$request->login_ip,
        'user_agent'=>$request->user_agent,
        'action'=>$request->action,
        'detail'=>$request->detail);
    $this->loginlog($log);
    return response()->json(['saved'=>true]);
    # code...
}

public function loginlog($data)
{

    $logs = new Loginactivitylogs();
    $logs->user_id  = $data['userid']; 
    $logs->ip = $data['ip'];
    $logs->user_agent = $data['user_agent'];
    $logs->action = $data['action'];
    $logs->detail = $data['detail'];
    $logs->save();
    return true;

    # code...
}
    public function login(Request $request)
    {
        
        
        $credentials = request(['email', 'password']);

      //  dd($credentials);
        //lockout event
         if($this->hasTooManyLoginAttempts($request)){
           
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if(!Auth::attempt($credentials)){
            $this->incrementLoginAttempts($request);
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = Auth::user();
        // print_r($user);exit;

        if(!$request->email || !$request->password){
            // return an error response
            return response()->json(['message'=>'Username or Password field empty'], 401);
        } else{
            $login_email = $request->email;
            $login_password = $request->password;

            $user = User::whereEmail($login_email)->first();
            // print_r($user);exit;
            if (! $user) {
                // return an error response
                return response()->json(['message'=>'Email not found'], 401);
            }
        
            if (!Hash::check($login_password, $user->password)) {
                // return an error response
                return response()->json(['message'=>'Please enter correct password'], 401);
            }
            
            $client = DB::table('oauth_clients')
            ->where('password_client', true)
            ->first();

            if (! $client) {
                // Passport not setup properly
                return response()->json(['message'=>'Passport not setup properly'], 401);
               
            }

            if($user->email_verified_at !== NULL){
                $tokenRequest = $request->create('/oauth/token', 'POST', $request->all());
                $request->request->add([
                    'grant_type' => 'password',
                    "client_id" => $client->id,
                    "client_secret" => $client->secret,
                    "username" => $login_email,
                    "password" => $login_password,
                    "scope" => $request->scope,
                ]);
                $response = Route::dispatch($tokenRequest);
                
                $json = (array)json_decode($response->getContent());
    
                if (!empty($json['error'])) {
                    $json['error'] = $json['message'];
                }

                $log = array('userid'=>$user->id,
                            'ip'=>$request->login_ip,
                            'user_agent'=>$request->user_agent,
                            'action'=>'login',
                            'detail'=>'login');
                $this->loginlog($log);
                $this->clearLoginAttempts($request);
                $Customermetadata = Customermetadata::where('user_id', $user->id)->first();
                // dd($Customermetadata);

                $json['userdata'] =
                    [
                        'id' => $user->id,
                        'uuid' => $user->uuid,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'mobile_number' => $user->mobile_number,
                        'email_verified_at' => $user->email_verified_at,
                        'status' => $user->status,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                        'deleted_at' => $user->deleted_at,
                       // 'customer_stripe_id' => $Customermetadata->user_stripe_customer_id
                    ];
    
                $response->setContent(json_encode($json));
   
                return $response;
            } else{
                return response()->json(['message'=>'Please Verify Email'], 401);
            }
        }

        

        // $request->validate([
        //     'email' => 'required|string|email|exists:users,email',
        //     'password' => 'required|string',
        //     'remember_me' => 'boolean'
        // ]);
        // $credentials = request(['email', 'password']);

        // if(!Auth::attempt($credentials))
        //     return response()->json([
        //         'message' => 'Unauthorized'
        //     ], 401);

        //     $user = Auth::user();
            
        //     // Add role as scope
        //     $userRole = $user->role()->first();
        //     // print_r($userRole);exit;
        // // $userRole = $user->roles ? $user->roles->first()->name : 'admin';

        // // if ($userRole) {
        // //     $this->scope = $userRole->role;
        // // }

        // $userdata = $request->user();
        // unset($userdata["id"]);

        // // Token based on user role (scope)
        // $tokenResult = $user->createToken('Personal Access Token', [
        //     $userRole->role
        // ]);
        // // $tokenResult = $user->createToken('Personal Access Token');
        // $token = $tokenResult->token;

        // if ($request->remember_me)
        //     $token->expires_at = Carbon::now()->addWeeks(1);

        // $token->save();
        
        // return response()->json([
        //     'access_token' => $tokenResult,
        //     'token_type' => 'Bearer',
        //     'expires_at' => Carbon::parse(
        //         $tokenResult->token->expires_at
        //     )->toDateTimeString(),
        //     'user_data' => $userdata
        // ]);
    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {


        $user = Auth::user();
        $log = array('userid'=>$user->id,
                'ip'=>$request->ip,
                'user_agent'=>$request->user_agent,
                'action'=>'logout',
                'detail'=>'logout');
        $this->loginlog($log);

        $request->user()->token()->revoke();
        //Auth::logout(); // logs out the user
        //Session::flush();

        //$request->session()->forget('key');
       
        return response()->json(['message'=>'Successfully logged out'], 200);
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        
        return response()->json($request->user());
    }
    public function username()
{
    return 'email';
}

    /**
    * details api
    *
    * @return \Illuminate\Http\Response
    */
    public function details()
    {
        $user = Auth::user();
        return response()->json([‘success’ => $user], $this-> successStatus);
    }
}
