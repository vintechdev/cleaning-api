<?php

namespace App\Services;

use App\NotificationLog;
use App\Repository\BookingServiceRepository;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Repository\BookingReqestProviderRepository;
use App\Repository\UserBadgeReviewRepository;
use App\Services\Bookings\BookingJobsManager;
use App\Bookingstatus;
/**
 * Class BookingStatusChangeEmailNotificationService
 * @package App\Services
 */
class BookingStatusChangeEmailNotificationService extends AbstractBookingNotificationService
{
    protected $booking;
    const TRANSACTION_SETTING_ID =1;
    protected $bookingservicerepo;
    protected $mailService;
    protected $userRepo;
    protected $userbadge;
    protected $bookingrequestprovider;

     /**
     * @var string
     */
    private $serviceName = '';
    

    public function __construct(
        BookingServiceRepository $bookingservicerepo,
        MailService $mailService,
        UserRepository $userRepo,
        BookingReqestProviderRepository $bookingrequestprovider,
        UserBadgeReviewRepository $userbadge
    ) {
        $this->bookingservicerepo = $bookingservicerepo;
        $this->mailService = $mailService;
        $this->userRepo = $userRepo;
        $this->bookingrequestprovider = $bookingrequestprovider;
        $this->userbadge = $userbadge;
    }
    

    protected function sendNotification(): bool
    {
        //when provider change the status send email to user and provider
        //return true;
        $useremail = $this->sendEmail();
        if($useremail){
            return true;
        }else{
            return false;
        }
    }

    private function setServiceName()
    {
        $category = $this->bookingservicerepo->getBookingCategory($this->booking->id);       
        $serviceName = "";

        if (!empty($category)) {
            $serviceName = $category->name;
        }

        $this->serviceName = $serviceName;
    }

    /**
     * @return string
     */
    protected function getNotificationType(): string
    {
        return NotificationLog::NOTIFICATION_TYPE_BOOKING_STATUS_CHANGE_EMAIL;
    }
    protected function sendEmail(){
    
      //  $notificationsetting = $this->userRepo->getUserNotification($this->booking->user_id,self::TRANSACTION_SETTING_ID);
      //  if($notificationsetting['email']==1){
            if($this->booking->booking_status_id ==2 || $this->booking->booking_status_id ==3 || $this->booking->booking_status_id ==4){
                $this->sendAccpted_Arrived_Completed_StatusChangeEmail();
                $this->sendProviderEmail();
                return true;
            }
       /*  }else{
            return false;
        } */
    }

    
    public function sendAccpted_Arrived_Completed_StatusChangeEmail()
    { 
        $bookingdata = $this->bookingservicerepo->BookingDetailsforMail($this->booking->id);
        $bookingproviders = $this->bookingrequestprovider->getBookingAccptedProvidersDetails($this->booking->id);
        $providername =  $bookingproviders[0]['provider_first_name'].' '.$bookingproviders[0]['provider_last_name'];
            
        if(in_array($this->booking->getStatus(),[Bookingstatus::BOOKING_STATUS_ACCEPTED,Bookingstatus::BOOKING_STATUS_ARRIVED,Bookingstatus::BOOKING_STATUS_COMPLETED])){   

            if($this->booking->getStatus() ==Bookingstatus::BOOKING_STATUS_ACCEPTED){
                $text =  $providername .' has accepted your Booking. Have fun working together!!';
                $subject =  $text;
                $status = 'accepted';
            }else if($this->booking->getStatus() ==Bookingstatus::BOOKING_STATUS_ARRIVED){
                $text =  $providername .' has arrived at your place. Have fun working together!!';
                $subject =  $text;
                $status = 'arrived';
            }else if($this->booking->getStatus() ==Bookingstatus::BOOKING_STATUS_COMPLETED){
                $text =  $providername .' has completed your services. Hope you enjoy his service!! Please share your review.';
                $subject =  $providername .' has completed your services. Hope you enjoy his service!!';
                $status = 'completed';
            }
            $subject =  $text;
            $bookingdata['text'] = $text;
            $bookingdata['provider_name'] = $providername ;
            $bookingdata['badge'] =  $this->userbadge->getBadgeDetails($bookingproviders[0]['provider_user_id']);
            $bookingdata['avgrate'] =  $this->userbadge->getAvgRating($bookingproviders[0]['provider_user_id']);
            $bookingdata['status'] = $status;

            $res = $this->mailService->send('email.statusaccepted_arrived_completed_emailtouser', $bookingdata, $bookingdata['userEmail'], $bookingdata['userName'], $subject);
            if($res){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
      

    }
    protected function sendProviderEmail(){
        $bookingproviders = $this->bookingrequestprovider->getBookingProvidersData($this->booking->id);
        $data = $this->bookingservicerepo->BookingDetailsforProviderEmail($this->booking->id,$this->booking->user_id);
        if(count($bookingproviders)>0){
            if(in_array($this->booking->booking_status_id,[Bookingstatus::BOOKING_STATUS_ACCEPTED,Bookingstatus::BOOKING_STATUS_ARRIVED,Bookingstatus::BOOKING_STATUS_COMPLETED])){  
            
                $providername =  $bookingproviders[0]['provider_first_name'].' '.$bookingproviders[0]['provider_last_name'];
                if($this->booking->getStatus() ==Bookingstatus::BOOKING_STATUS_ACCEPTED){
                     $text =  'You have accepted Booking - '.$this->booking->id;
                     $subject =  $text;
                    $status = 'accepted';
                }else if($this->booking->getStatus() == Bookingstatus::BOOKING_STATUS_ARRIVED){
                    $text =  'You have arrived for Booking - '.$this->booking->id;
                    $subject =  $text;
                    $status = 'arrived';
                }else if($this->booking->getStatus() == Bookingstatus::BOOKING_STATUS_COMPLETED){
                    $text =  'You have completed Booking - '.$this->booking->id.' Please share your review for user!!';
                    $subject =  'You have completed Booking - '.$this->booking->id;
                    $status = 'completed';
                }
                $userEmail = $bookingproviders[0]['email'];
                $userName = $providername ;
                $data['text'] = $text;
                $data['providers_name'] = $providername ;
                $data['status'] = $status;

                $res = $this->mailService->send('email.status_accepted_arrived_completed_email_to_provider', $data, $userEmail, $userName, $subject);
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
