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

        if($this->booking->booking_status_id ==2 || $this->booking->booking_status_id ==3 || $this->booking->booking_status_id ==4){
            $this->sendAccpted_Arrived_Completed_StatusChangeEmail();
            $this->sendProviderEmail();
            return true;
        } else if ($this->booking->booking_status_id == 5 
            && $this->booking->getUserId() == auth()->user()->id) {
            $providers = $this->bookingrequestprovider->getBookingProvidersData($this->booking->id);
            $user = $this->userRepo->getUserDetails($this->booking->getUserId())[0];
          
            $this->emailToUserForCancellation($user, null, true);
            $this->emailToProvidersForCancellation($providers, $user, false);

        } else if ($this->booking->booking_status_id == 5 
            && $this->booking->getUserId() !== auth()->user()->id
        ) {
            $user = $this->userRepo->getUserDetails($this->booking->getUserId())[0];
            $providerId = request()->get('user_id') ? request()->get('user_id'): auth()->user()->id;
            $provider =  $this->userRepo->getProviderDetails($providerId);

            $providers = array($provider);

            $this->emailToUserForCancellation($user, $provider, false);
            $this->emailToProvidersForCancellation($providers, $user, true);

        }  else if ($this->booking->booking_status_id == 6) {
            $countPendingProviders = $this->bookingrequestprovider->getCountWithStatuses([
                Bookingstatus::BOOKING_STATUS_PENDING
            ], $this->booking->getId());

            $this->emailToUserOnBookingReject($countPendingProviders);   
        }
    }


    private function emailToUserOnBookingReject($count = 0) {
        if ($count == 0) {
            $text = "";
            $providername = null;
            $bookingdata = $this->bookingservicerepo->BookingDetailsforMail($this->booking->id);
            $text =  'Your booking #('. $this->booking->id .') has been REJECTED ';
            $subject =  $text;
            $status = 'rejected';

            $subject =  $text;
            $bookingdata['text'] = $text;
            $bookingdata['provider_name'] = $providername;
            $bookingdata['badge'] =  [];
            $bookingdata['avgrate'] =  [];
            $bookingdata['status'] = $status;
            $bookingdata['service_category_name'] = $this->serviceName;

            return $this->mailService->send('email.statusaccepted_arrived_completed_emailtouser', 
            $bookingdata, $bookingdata['userEmail'], $bookingdata['userName'], $subject);
        }
    }


    private function emailToProvidersForCancellation($providers = array(), $user, $cancelledByProvider = false)
    {
        foreach ($providers as $key => $provider) {
            $provider = (array) $provider;

            if ($cancelledByProvider) {
                $text = 'You have changed Booking #('.$this->booking->id .') status to CANCELLED.';
                $subject =  'You have CANCELLED Booking - '.$this->booking->id;
            } else {
                $username = $user['first_name'] .' '.  $user['last_name'];
                $text = $username. ' has changed Booking #('.$this->booking->id .') status to CANCELLED.';
                $subject = $username. ' has CANCELLED Booking - '.$this->booking->id;
            }   
            
            $userEmail = $provider['email'];
            $userName = $provider['provider_first_name'] .' '.  $provider['provider_last_name'];
            $data['text'] = $text;
            $data['providers_name'] = $provider['provider_first_name'] .' '.  $provider['provider_last_name'];
            $data['status'] = 'cancelled';
            $bookingdata['service_category_name'] = $this->serviceName;

            $res = $this->mailService->send('email.status_accepted_arrived_completed_email_to_provider', $data, $userEmail, $userName, $subject);

        }
        
    }

    private function emailToUserForCancellation($user, $provider = null, $cancelledByUser = false) {
        $text = "";
        $providername = "";
        $bookingdata = $this->bookingservicerepo->BookingDetailsforMail($this->booking->id);
        
        if ($cancelledByUser) {
            $text =  'You have changed booking #('. $this->booking->id .') status to CANCELLED.';
        } else {
            $providername = ($provider ? ($provider['provider_first_name'] . ' ' .  $provider['provider_first_name']) : null);
            $text =  $providername .' has changed booking #('. $this->booking->id .') status to CANCELLED.';
        }

        $subject =  $text;
        $status = 'cancelled';

        $subject =  $text;
        $bookingdata['text'] = $text;
        $bookingdata['provider_name'] = $providername;
        $bookingdata['badge'] =  [];
        $bookingdata['avgrate'] =  0;
        $bookingdata['status'] = $status;
        $bookingdata['service_category_name'] = $this->serviceName;

        return $this->mailService->send('email.statusaccepted_arrived_completed_emailtouser', 
        $bookingdata, $bookingdata['userEmail'], $bookingdata['userName'], $subject);
    }


    
    public function sendAccpted_Arrived_Completed_StatusChangeEmail()
    { 
        $bookingdata = $this->bookingservicerepo->BookingDetailsforMail($this->booking->id);
        $bookingproviders = $this->bookingrequestprovider->getBookingAccptedProvidersDetails($this->booking->id);
       
            
        if(in_array($this->booking->getStatus(),[Bookingstatus::BOOKING_STATUS_ACCEPTED,Bookingstatus::BOOKING_STATUS_ARRIVED,Bookingstatus::BOOKING_STATUS_COMPLETED])){

            $providername =  $bookingproviders[0]['provider_first_name'].' '.$bookingproviders[0]['provider_last_name'];

            if($this->booking->getStatus() ==Bookingstatus::BOOKING_STATUS_ACCEPTED){
                $text =  $providername .' has changed booking #('. $this->booking->id .') status to ACCEPTED. Have fun working together!!';
                $subject =  $text;
                $status = 'accepted';
            } else if($this->booking->getStatus() ==Bookingstatus::BOOKING_STATUS_ARRIVED){
                $text =  $providername .' has changed booking #('. $this->booking->id .') status to ARRIVED. Have fun working together!!';
                $subject =  $text;
                $status = 'arrived';
            } else if($this->booking->getStatus() ==Bookingstatus::BOOKING_STATUS_COMPLETED){
                $text =  $providername .'  has changed booking #('. $this->booking->id .') status to COMPLETED. Hope you enjoy his service!! Please share your review.';
                $subject =  $providername .' has completed your services. Hope you enjoy his service!!';
                $status = 'completed';
            }

            $subject =  $text;
            $bookingdata['text'] = $text;
            $bookingdata['provider_name'] = $providername ;
            $bookingdata['badge'] =  $this->userbadge->getBadgeDetails($bookingproviders[0]['provider_user_id']);
            $bookingdata['avgrate'] =  $this->userbadge->getAvgRating($bookingproviders[0]['provider_user_id']);
            $bookingdata['status'] = $status;
            $bookingdata['service_category_name'] = $this->serviceName;

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
                     $text =  'You have changed Booking #('.$this->booking->id .') status to ACCEPTED';
                     $subject =  $text;
                    $status = 'accepted';
                }else if($this->booking->getStatus() == Bookingstatus::BOOKING_STATUS_ARRIVED){
                    $text =  'You have changed Booking #('.$this->booking->id .') status to ARRIVED';
                    $subject =  $text;
                    $status = 'arrived';
                }else if($this->booking->getStatus() == Bookingstatus::BOOKING_STATUS_COMPLETED){
                    $text = 'You have changed Booking #('.$this->booking->id .') status to COMPLETED. Please share your review for user!!';
                    $subject =  'You have completed Booking - '.$this->booking->id;
                    $status = 'completed';
                }
                $userEmail = $bookingproviders[0]['email'];
                $userName = $providername ;
                $data['text'] = $text;
                $data['providers_name'] = $providername ;
                $data['status'] = $status;
                $bookingdata['service_category_name'] = $this->serviceName;

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
