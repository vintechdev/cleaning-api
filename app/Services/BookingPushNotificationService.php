<?php

namespace App\Services;

use App\Notification;
use App\NotificationLog;
use App\Booking;
use App\PushNotificationLogs;
use App\Repository\BookingServiceRepository;
use App\Repository\Eloquent\NotificationLogRepository;
use App\Repository\UserNotificationRepository;
use App\Repository\UserRepository;
use App\Repository\BookingReqestProviderRepository;
use Illuminate\Support\Facades\Auth;

/**
 * Class BookingPushNotificationService
 * @package App\Services
 */
class BookingPushNotificationService extends AbstractBookingNotificationService
{
    /**
     * @var Booking
     */
    protected $booking;

    /**
    * @var BookingServiceRepository
    */
    protected $bookingservicerepo;
    protected $mailService;
    /**
     * @var UserRepository
     */
    protected $userRepo;
    protected $bookingrequestprovider;
    const TRANSACTION_SETTING_ID = 1;
    /**
     * @var UserNotificationRepository
     */
    private $userNotificationRepo;

    /**
    * @var string
    */
    private $serviceName;

    public function __construct(NotificationLogRepository $notificationLogRepository){
        parent::__construct($notificationLogRepository);
        $this->bookingservicerepo = app(BookingServiceRepository::class);
        $this->userRepo = app(UserRepository::class);
        $this->bookingrequestprovider = app(BookingReqestProviderRepository::class);
        $this->userNotificationRepo =  app(UserNotificationRepository::class);
    }

    protected function sendNotification(): bool
    {
        $this->setServiceName();
        $sentUser = $this->sendUserPushNotification();
        $sentProvider = $this->sendPushNotificationToAllProviders();
        if($sentUser || $sentProvider){
            return true;
        }

        return false;
    }

    protected function getNotificationType(): string
    {
        return NotificationLog::NOTIFICATION_TYPE_BOOKING_CREATED_PUSH;
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


    public function sendUserPushNotification()
    {
        $notificationSetting = $this->userNotificationRepo
            ->getUserNotificationType(Auth::user()->id, Notification::BOOKING_UPDATES, 'right');

        if (!$notificationSetting || ($notificationSetting && $notificationSetting->allow_push != 0 && (!$notificationSetting->push))) {
            return false;
        }

        $logData = [
            'event_type' => $this->getNotificationType(),
            'user_id' => Auth::user()->id,
            'booking_id' => $this->booking->id,
            'user_type' => NotificationLog::NOTIFICATION_LOG_USER_TYPE_USER,
        ];

        $notificationLog = $this->notificationLogRepo->create($logData);

        $title =  PushNotificationLogs::PUSH_NOTIFICATION_LOG_USER['new_booking']['title'];
        $message = PushNotificationLogs::PUSH_NOTIFICATION_LOG_USER['new_booking']['message'];

        $title = str_replace('{booking-id}', $this->booking->id, $title);
        $message = str_replace('{default-service-name}', $this->serviceName, $message);

        // TODO: can be change later in repo
        PushNotificationLogs::query()->create([
            'notification_log_id' => $notificationLog->id,
            'title' => $title,
            'message' => $message,
            'status' => PushNotificationLogs::STATUS_UNREAD,
        ]);

        return true;
    }

    protected function getProviderNotificationType(): string
    {
        return NotificationLog::NOTIFICATION_TYPE_BOOKING_CREATED_PUSH;
    }

    public function sendPushNotificationToAllProviders()
    {
        $bookingproviders = $this->bookingrequestprovider->getBookingProvidersData($this->booking->id);

        if(count($bookingproviders) === 0){
           return false;
        }

        $title =  PushNotificationLogs::PUSH_NOTIFICATION_LOG_PROVIDER['new_booking']['title'];
        $message = PushNotificationLogs::PUSH_NOTIFICATION_LOG_PROVIDER['new_booking']['message'];

        $title = str_replace('{booking-id}', $this->booking->id, $title);
        $message = str_replace('{default-service-name}', $this->serviceName, $message);

        $send = false ;
        foreach ($bookingproviders as $k => $provider) {
            $notificationSetting = $this->userNotificationRepo
                ->getUserNotificationType($provider['provider_user_id'], Notification::REQUEST_TO_PROVIDER);

            if (!$notificationSetting || ($notificationSetting && $notificationSetting->push === 0)) {
                continue;
            }

            $logData = [
                'event_type' => $this->getProviderNotificationType(),
                'user_id' => $provider['provider_user_id'],
                'booking_id' => $this->booking->id,
                'user_type' => NotificationLog::NOTIFICATION_LOG_USER_TYPE_PROVIDER,
            ];

            $notificationLog = $this->notificationLogRepo->create($logData);
            
            PushNotificationLogs::query()->create([
                'notification_log_id' => $notificationLog->id,
                'title' => $title,
                'message' => $message,
                'status' => PushNotificationLogs::STATUS_UNREAD,
            ]);

            $send = true;
        }

        return $send;
    }
}