<?php
/**
 * Created by PhpStorm.
 * User: rana
 * Date: 8/3/17
 * Time: 4:52 PM
 */

namespace App\Services;
use Config;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class MailService
{
    protected $defaultEmail;
    protected $defaultName;

    public function __construct()
    {
        //$default = $data['adm_setting'] = allsetting();
        $this->defaultEmail = env('MAIL_FROM_ADDRESS');//Config::get('const.MAIL_FROM_ADDRESS');
        $this->defaultName = 'Cleaning Service';//Config::get('const.MAIL_FROM_NAME');
    }


    public function send($template = '', $data = [], $to = '', $name = '', $subject = '')
    {
        try {
           // echo $this->defaultEmail;exit;
            Mail::send($template,array('data' => $data), function ($message) use ($name, $to, $subject) {
                $message->to($to, $name)->subject($subject)->replyTo(
                    $this->defaultEmail, $this->defaultName
                );
                $message->from($this->defaultEmail, $this->defaultName);

            });
            return true;
        }catch (\Exception $e){
            return $e->getMessage();
//            Session::flash('dismiss', 'Unavailable email service!');
        }
    }

}