<?php


namespace App\Services;

use App\NotificationLog;
use App\Booking;
use App\Repository\BookingServiceRepository;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Repository\BookingReqestProviderRepository;
/**
 * Class BookingEmailNotificationService
 * @package App\Services
 */
class BookingEmailNotificationService extends AbstractBookingNotificationService
{
    protected $booking;
    protected $bookingservicerepo;
    protected $mailService;
    protected $userRepo;
    protected $bookingrequestprovider;
    const TRANSACTION_SETTING_ID =1;
    public function __construct(){
        $this->bookingservicerepo = app(BookingServiceRepository::class);
        $this->mailService = app(MailService::class);
        $this->userRepo = app(UserRepository::class);
        $this->bookingrequestprovider = app(BookingReqestProviderRepository::class);

    }
    protected function sendNotification(): bool
    {
       // return false;
        $useremail = $this->sendUserEmail();
        $providerEmail = $this->sendProviderEmail();
        if($useremail==true && $providerEmail==true){
            return true;
        }else{
            return false;
        }
    }

    protected function getNotificationType(): string
    {
        return NotificationLog::NOTIFICATION_TYPE_BOOKING_CREATED_EMAIL;
    }
    public function sendProviderEmail(){
        $bookingproviders = $this->bookingrequestprovider->getBookingProvidersData($this->booking->id);
        $data = $this->bookingservicerepo->BookingDetailsforProviderEmail($this->booking->id,$this->booking->user_id);
        if(count($bookingproviders)>0){
           foreach($bookingproviders as $k=>$v){
                $userEmail = $v['email'];
                $userName = $v['provider_first_name'];
                $subject = 'New Service Request Received.';
                $data['providers_name'] = $v['provider_first_name'];
                $res = $this->mailService->send('email.bookingrequestprovider', $data, $userEmail, $userName, $subject);
            }
        }
        if($res){
            return true;
        }else{
            return false;
        }
    }
    public function sendUserEmail()
    {
       //user email
       $bookingid = $this->booking->id;
       $notificationsetting = $this->userRepo->getUserNotification(Auth::user()->id,self::TRANSACTION_SETTING_ID);
       if($notificationsetting['email']==1){
           $bookingdata = $this->bookingservicerepo->BookingDetailsforMail( $this->booking->id);
           //TODO: Check if the user have opted to send email. If not return false. If yes, add logic to send email here and return true.
           $res = $this->mailService->send('email.bookingcreated', $bookingdata, $bookingdata['userEmail'], $bookingdata['userName'], $bookingdata['subject']);
           if($res){
               return true;
           }else{
               return false;
           }
       }else{
           return false;
       }
    }
}