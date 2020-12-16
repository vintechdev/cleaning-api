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
use Route;
use DB;

use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Hash;
// for email verify
use Stripe;
use Validator;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Events\Verified;

class AuthController extends Controller
{
    use VerifiesEmails;
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
    public function register(Request $request)
    {
      //  dd($request->all());
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
        // $is_enduser = $request->get('is_enduser');
        // $is_provider = $request->get('is_provider');
        // $is_admin = $request->get('is_admin');
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
        // $stripeCustomer = $User->createAsStripeCustomer();
        // if($stripeCustomer->id){
        //     $success['stripe'] = 'Customer created into stripe account successfully.';
        // } else{
        //     $success['stripe'] = 'Failed to create customer into stripe account.';
        // }
        // dd($stripeCustomer);

//         $stripe = new \Stripe\StripeClient(
//   'sk_test_tVIWBNGg2HuiCv7zfMr3Tiit'
// );
// $stripe->accounts->create([
//   'type' => 'custom',
//   'country' => 'US',
//   'email' => 'jenny.rosen@example.com',
//   'business_type' => 'individual',
//   'capabilities' => [
//     'card_payments' => ['requested' => true],
//     'transfers' => ['requested' => true],
//   ],
// ]);
        $lastinsertid = $User->id;

//         $stripe = new \Stripe\StripeClient(
//   'sk_test_tVIWBNGg2HuiCv7zfMr3Tiit'
// );
/* Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

  $account = Stripe\Account::create([
    'country' => 'US',
    'type' => 'custom',
    'requested_capabilities' => ['card_payments', 'transfers'],
  ]); */
        
       // print_r($lastinsertid);exit;
        // for email verify
       // $User->sendApiEmailVerificationNotification();
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
                /* $stripeCustomer = $Customermetadata->createAsStripeCustomer($options, $user_id);
                if($stripeCustomer->id){
                    $success['stripe'] = 'Customer created into stripe account successfully.';
                } else{
                    $success['stripe'] = 'Failed to create customer into stripe account.';
                } */
            }

            if($r == 'provider'){
                $User = User::firstOrNew(['id' => $lastinsertid]);
                $User->providertype = $request->get('provider_type');
                $User->save();
            }
        }
        
        // $email = $request->get('email');

        // $data = ([
        //  'first_name' => $request->get('first_name'),
        //  'email' => $request->get('email'),
        //  'mobile_number' => $request->get('mobile_number'),
        //  ]);

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
    public function login(Request $request)
    {
        // echo "login";exit;

        $validator = $request->validate([
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string'
        ]);
        $credentials = request(['email', 'password']);
        // print_r($credentials);exit;

        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 201);

        $user = Auth::user();
        // print_r($user);exit;

        if(!$request->email || !$request->password){
            // return an error response
            return response()->json(['message'=>'Username or Password field empty'], 201);
        } else{
            $login_email = $request->email;
            $login_password = $request->password;

            $user = User::whereEmail($login_email)->first();
            // print_r($user);exit;
            if (! $user) {
                // return an error response
                return response()->json(['message'=>'Email not found'], 201);
            }
        
            if (!Hash::check($login_password, $user->password)) {
                // return an error response
                return response()->json(['message'=>'Please enter correct password'], 201);
            }
            
            $client = DB::table('oauth_clients')
            ->where('password_client', true)
            ->first();

            if (! $client) {
                // Passport not setup properly
                return response()->json(['message'=>'Passport not setup properly'], 201);
               
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
                        'customer_stripe_id' => $Customermetadata->user_stripe_customer_id
                    ];
    
                $response->setContent(json_encode($json));
    
                return $response;
            } else{
                return response()->json(['message'=>'Please Verify Email'], 201);
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
        Auth::logout(); // logs out the user 
        //Session::flush();
        $request->user()->token()->revoke();
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
