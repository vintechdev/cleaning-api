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
    public function verify(Request $request) {
        // echo $request->get('expires');exit;
        $userID = $request['id'];
        $user = User::findOrFail($userID);
        // print_r($user->email_verified_at);exit;

        if($user->email_verified_at !== NULL){
            return response()->json('Email already verified!');
        }

        $expires_at = $request->get('expires');
        $current_timestamp = time();
        if($current_timestamp > $expires_at){
            $user->sendApiEmailVerificationNotification();
            return redirect(env('FRONT_URL').'signin?expired=true');
           // return response()->json('This link is expired! Check your email for new link.');
        }
        
        $date = date('Y-m-d g:i:s');
        $user->email_verified_at = $date; // to enable the “email_verified_at field of that user be a current time stamp by mimicing the must verify email feature
        $user->status = "active";
        $user->save();
        return redirect(env('FRONT_URL').'signin?verified=true');
      //  return response()->json('Email verified!');
    }
    /**
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