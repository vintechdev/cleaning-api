<?php
namespace App\Http\Controllers;
use App\User;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
class VerificationApiController extends Controller
{
    use VerifiesEmails;
    /**
    * Show the email verification notice.
    *
    */
    public function show()
    {
        //
    }
    /**
    * Mark the authenticated user’s email address as verified.
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function verify($id,Request $request){
       
        $userID = $id;
        $user = User::findOrFail($userID);
       
        if($user->email_verified_at !== NULL){
            return response()->json(['error'=>true,'message'=>'Email has been already verified!'],401);
        }

        $expires_at = $request->get('expires');
        $current_timestamp = time();
        if($current_timestamp > $expires_at){
            $user->sendApiEmailVerificationNotification();
            return response()->json(['error'=>true,'message'=>'This link is expired! Check your email for new link.'],401);
        }
        
        $date = date('Y-m-d h:i:s');
        $user->email_verified_at = $date; // to enable the “email_verified_at field of that user be a current time stamp by mimicing the must verify email feature
        $user->status = "active";
        $user->save();
        return response()->json(['success'=>true,'message'=>'Email has been verified successfully!! Please login.'],200);
    }
    /**
     * 
    * Resend the email verification notification.
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json('User already have verified email!', 422);
            // return redirect($this->redirectPath());
        }
        $request->user()->sendEmailVerificationNotification();
        return response()->json('The notification has been resubmitted');
        // return back()->with('resent', true);
    }
}