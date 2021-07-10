<?php

namespace App\Services;

use App\Notification;
use App\NotificationLog;
use App\PushNotificationLogs;
use App\Repository\Eloquent\NotificationLogRepository;
use App\Repository\SmsNotificationLogRepo;
use App\Repository\UserNotificationRepository;
use App\Repository\BookingReqestProviderRepository;
use App\Bookingstatus;
use App\SMSNotificationLogs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use App\Repository\BookingServiceRepository;

/**
 * Class BookingStatusChangeSmsNotificationService
 * @package App\Services
 */
class BookingStatusChangeSmsNotificationService extends AbstractBookingNotificationService
{
    /**
     * @var BookingReqestProviderRepository
     */
    protected $bookingRequestProvider;

    /**
    * @var BookingServiceRepository
    */
    protected $bookingservicerepo;

    /**
     * @var UserNotificationRepository
     */
    private $userNotificationRepo;
    /**
     * @var SmsNotificationLogRepo
     */
    private $smsNotificationLogRepo;


    /**
     * @var string
     */
    private $serviceName;


    public function __construct(
        NotificationLogRepository $notificationLogRepository,
        BookingReqestProviderRepository $bookingRequestProvider,
        SmsNotificationLogRepo $smsNotificationLogRepo
    ) {
        parent::__construct($notificationLogRepository);

        $this->bookingRequestProvider = $bookingRequestProvider;
        $this->userNotificationRepo =  app(UserNotificationRepository::class);
        $this->smsNotificationLogRepo = $smsNotificationLogRepo;
        $this->bookingservicerepo = app(BookingServiceRepository::class);
    }
    

    protected function sendNotification(): bool
    {
        if (!Config::get('services.sms.enabled')) {
            return false;
        }

        $this->setServiceName();

        $status =  $this->getBookingStatusByBooking();

        if ($this->createUserSmsNotification($status)) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    protected function getNotificationType(): string
    {
        return NotificationLog::NOTIFICATION_TYPE_BOOKING_STATUS_CHANGE_SMS;
    }

    private function getBookingStatusByBooking(): string {
        return Bookingstatus::getStatusNameById($this->booking->getStatus());
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

    public function createUserSmsNotification($status)
    {
        $countPendingProviders = 0;

        if ($this->booking->getStatus() === Bookingstatus::BOOKING_STATUS_REJECTED) {
            $countPendingProviders = $this->bookingRequestProvider->getCountWithStatuses([
                Bookingstatus::BOOKING_STATUS_PENDING
            ], $this->booking->getId());
        }

        /** Notify only to provider if still provider are having pending status **/
        if ($countPendingProviders > 0) {
            $this->createNotificationLog(Auth::user()->id,
                $this->getProviderNotificationMessage($status)
            );
            return true;
        }

        $notificationSetting = $this->getNotificationSetting($this->booking->getUserId());

        if (!$notificationSetting || ($notificationSetting && $notificationSetting->allow_sms != 0 && (!$notificationSetting->sms))) {
            return false;
        }

        $logData = [
            'event_type' => $this->getNotificationType(),
            'user_id' => $this->booking->getUserId(),
            'booking_id' => $this->booking->getId(),
            'user_type' => NotificationLog::NOTIFICATION_LOG_USER_TYPE_USER,
        ];

        $notificationLog = $this->notificationLogRepo->create($logData);


        $message = SMSNotificationLogs::getMessage('booking_status_update', 'user');
        $message = str_replace('{booking-id}', $this->booking->id, $message);
        $message = str_replace('{default-service-name}', $this->serviceName, $message);
        $message = str_replace('{booking-status}', strtoupper($status), $message);

    
        // TODO: can be change later in repo
        $this->smsNotificationLogRepo->create([
            'notification_log_id' => $notificationLog->id,
            'message' => $message,
            'status' => SMSNotificationLogs::STATUS_PENDING,
        ]);

        $this->sendProviderSmsNotfication($status);

        return true;
    }

    protected function sendProviderSmsNotfication($status) {

        $message = $this->getProviderNotificationMessage($status);

        if(in_array($this->booking->getStatus(),[
            Bookingstatus::BOOKING_STATUS_ACCEPTED,
            Bookingstatus::BOOKING_STATUS_ARRIVED,
            Bookingstatus::BOOKING_STATUS_COMPLETED,
            Bookingstatus::BOOKING_STATUS_APPROVED,
            Bookingstatus::BOOKING_STATUS_PENDING,
        ])) {
            $bookingProviders = $this->bookingRequestProvider
                ->getBookingAccptedProvidersDetails($this->booking->getId());

            foreach ($bookingProviders as $k => $provider) {
                $this->createNotificationLog($provider['provider_user_id'],
                    $this->getProviderNotificationMessage($status)
                );
            }
        } else if ($this->booking->getStatus() === Bookingstatus::BOOKING_STATUS_REJECTED) {
            // Notify last provider
            $this->createNotificationLog(Auth::user()->id,
                $this->getProviderNotificationMessage($status)
            );
        } else if ($this->booking->getStatus() === Bookingstatus::BOOKING_STATUS_CANCELLED
            && $this->booking->getUserId() !== Auth::user()->id) {
            $this->createNotificationLog(Auth::user()->id,
                $this->getProviderNotificationMessage($status)
            );
        } else if ($this->booking->getStatus() === Bookingstatus::BOOKING_STATUS_CANCELLED
            && $this->booking->getUserId() === Auth::user()->id) {
            $bookingProviders = $this->bookingRequestProvider->getBookingProvidersData($this->booking->id);

            if(count($bookingProviders) === 0){
                return false;
            }

            foreach ($bookingProviders as $k => $provider) {
                $this->createNotificationLog(
                    $provider['provider_user_id'],
                    $message
                    //$this->getProviderNotificationMessage($status)
                );
            }
        }

        return true;
    }

    private function getProviderNotificationMessage($status): string {
        $message = SMSNotificationLogs::getMessage('booking_status_update', 'provider');
        $message = str_replace('{booking-id}', $this->booking->id, $message);
        $message = str_replace('{default-service-name}', $this->serviceName, $message);
        $message = str_replace('{booking-status}', strtoupper($status), $message);

        return $message;
    }

    private function getNotificationSetting($userId) {
        return $notificationSetting = $this->userNotificationRepo
            ->getUserNotificationType($userId, Notification::BOOKING_UPDATES);
    }

    private function createNotificationLog($userId, $message) {
        $notificationSetting = $this->getNotificationSetting($userId);

        if (!$notificationSetting || ($notificationSetting && $notificationSetting->sms === 0)) {
            return;
        }

        $logData = [
            'event_type' => $this->getNotificationType(),
            'user_id' => $userId,
            'booking_id' => $this->booking->getId(),
            'user_type' => NotificationLog::NOTIFICATION_LOG_USER_TYPE_PROVIDER,
        ];

        $notificationLog = $this->notificationLogRepo->create($logData);
        $this->smsNotificationLogRepo->create([
            'notification_log_id' => $notificationLog->id,
            'message' => $message,
            'status' => SMSNotificationLogs::STATUS_PENDING,
        ]);
    }
}
