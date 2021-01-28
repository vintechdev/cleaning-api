<?php

namespace App\Services;

use App\NotificationLog;
use App\Repository\BookingServiceRepository;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Repository\BookingReqestProviderRepository;
use App\Repository\UserBadgeReviewRepository;
use App\Services\Bookings\BookingJobsManager;
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
    protected $bookingrequestprovider;
    

    public function __construct(){
        $this->bookingservicerepo = app(BookingServiceRepository::class);
        $this->mailService = app(MailService::class);
        $this->userRepo = app(UserRepository::class);
        $this->bookingrequestprovider = app(BookingReqestProviderRepository::class);
        $this->userbadge = app(UserBadgeReviewRepository::class);
      
    }
    

    protected function sendNotification(): bool
    {
        //when provider change the status send email to user and provider
        $useremail = $this->sendEmail();
        if($useremail){
            return true;
        }else{
            return false;
        }
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
        if(count($bookingproviders)>0){
            $providername =  $bookingproviders[0]['provider_first_name'].' '.$bookingproviders[0]['provider_last_name'];
            if($this->booking->booking_status_id ==2){
                 $text =  $providername .' has accepted your Booking. Have fun working together!!';
                 $subject =  $text;
            }else if($this->booking->booking_status_id ==3){
                $text =  $providername .' has arrived at your place. Have fun working together!!';
                $subject =  $text;
            }else if($this->booking->booking_status_id ==4){
                $text =  $providername .' has completed your services. Hope you enjoy his service!! Please share your review.';
                $subject =  $providername .' has completed your services. Hope you enjoy his service!!';
            }
            $subject =  $text;
            $bookingdata['text'] = $text;
            $bookingdata['provider_name'] = $providername ;
            $bookingdata['badge'] =  $this->userbadge->getBadgeDetails($bookingproviders[0]['provider_user_id']);
            $bookingdata['avgrate'] =  $this->userbadge->getAvgRating($bookingproviders[0]['provider_user_id']);

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
            
                $providername =  $bookingproviders[0]['provider_first_name'].' '.$bookingproviders[0]['provider_last_name'];
                if($this->booking->booking_status_id ==2){
                     $text =  'You have accepted Booking - '.$this->booking->id;
                     $subject =  $text;
                }else if($this->booking->booking_status_id ==3){
                    $text =  'You have arrived for Booking - '.$this->booking->id;
                    $subject =  $text;
                }else if($this->booking->booking_status_id ==4){
                    $text =  'You have completed Booking - '.$this->booking->id.' Please share your review for user!!';
                    $subject =  'You have completed Booking - '.$this->booking->id;
                }
                $userEmail = $bookingproviders[0]['email'];
                $userName = $providername ;
                $data['text'] = $text;
                $data['providers_name'] = $providername ;
                $res = $this->mailService->send('email.status_accepted_arrived_completed_email_to_provider', $data, $userEmail, $userName, $subject);
                return true;
           
        }else{
            return false;
        }
    }
}