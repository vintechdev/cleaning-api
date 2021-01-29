<?php
namespace App\Notifications;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
class VerifyApiEmail extends VerifyEmailBase
{
    /**
    * Get the verification URL for the given notifiable.
    *
    * @param mixed $notifiable
    * @return string
    */
    public function __construct($url)
    {
        $this->url = $url;
    }
    protected function verificationUrl($notifiable)
    {
       
        $verifyurl =  URL::temporarySignedRoute(
            'verificationapi.verify', Carbon::now()->addMinutes(60), ['id' => $notifiable->getKey()],false
        ); // this will basically mimic the email endpoint with get request
        $verifyurl = str_replace('/api','',$verifyurl);
        $returnurl = $this->url.$verifyurl;
        return $returnurl;
    }
}