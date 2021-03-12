<?php

namespace App\Services;

use App\Notification;
use App\NotificationLog;
use App\Booking;
use App\PushNotificationLogs;
use App\Repository\BookingServiceRepository;
use App\Repository\Eloquent\NotificationLogRepository;
use App\Repository\SmsNotificationLogRepo;
use App\Repository\UserNotificationRepository;
use App\Repository\UserRepository;
use App\Repository\BookingReqestProviderRepository;
use App\SMSNotificationLogs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

/**
 * Class BookingSmsNotificationService
 * @package App\Services
 */
class BookingSmsNotificationService extends AbstractBookingNotificationService
{
    /**
     * @var Booking
     */
    protected $booking;
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
     * @var SmsNotificationLogRepo
     */
    private $smsNotificationLogRepo;

    public function __construct(NotificationLogRepository $notificationLogRepository, SmsNotificationLogRepo $smsNotificationLogRepo){
        parent::__construct($notificationLogRepository);
        $this->bookingservicerepo = app(BookingServiceRepository::class);
        $this->userRepo = app(UserRepository::class);
        $this->bookingrequestprovider = app(BookingReqestProviderRepository::class);
        $this->userNotificationRepo =  app(UserNotificationRepository::class);
        $this->smsNotificationLogRepo = $smsNotificationLogRepo;
    }

    protected function sendNotification(): bool
    {
        if (!Config::get('services.sms.enabled')) {
            return false;
        }

        $sentUser = $this->createUserSms();
        $sentProvider = $this->createProviderSms();
        if($sentUser || $sentProvider){
            return true;
        }

        return false;
    }

    protected function getNotificationType(): string
    {
        return NotificationLog::NOTIFICATION_TYPE_BOOKING_CREATED_SMS;
    }

    public function createUserSms()
    {
        $notificationSetting = $this->userNotificationRepo
            ->getUserNotificationType(Auth::user()->id, Notification::BOOKING_UPDATES, 'right');

        if (!$notificationSetting || ($notificationSetting && $notificationSetting->allow_sms != 0 && (!$notificationSetting->sms))) {
            return false;
        }

        $logData = [
            'event_type' => $this->getNotificationType(),
            'user_id' => Auth::user()->id,
            'booking_id' => $this->booking->id,
            'user_type' => NotificationLog::NOTIFICATION_LOG_USER_TYPE_USER,
        ];

        $notificationLog = $this->notificationLogRepo->create($logData);

        $this->smsNotificationLogRepo->create([
            'notification_log_id' => $notificationLog->id,
            // TODO: add sms template message
            'message' => PushNotificationLogs::PUSH_NOTIFICATION_LOG_USER['new_booking']['message'],
            'status' => SMSNotificationLogs::STATUS_PENDING,
        ]);

        return true;
    }

    protected function getProviderNotificationType(): string
    {
        return NotificationLog::NOTIFICATION_TYPE_BOOKING_CREATED_SMS;
    }

    public function createProviderSms()
    {
        $bookingproviders = $this->bookingrequestprovider->getBookingProvidersData($this->booking->getId());

        if(count($bookingproviders) === 0){
           return false;
        }

        $send = false ;
        foreach ($bookingproviders as $k => $provider) {
            $notificationSetting = $this->userNotificationRepo
                ->getUserNotificationType($provider['provider_user_id'], Notification::REQUEST_TO_PROVIDER);

            if (!$notificationSetting || ($notificationSetting && $notificationSetting->sms === 0 )) {
                continue;
            }

            $logData = [
                'event_type' => $this->getProviderNotificationType(),
                'user_id' => $provider['provider_user_id'],
                'booking_id' => $this->booking->getId(),
                'user_type' => NotificationLog::NOTIFICATION_LOG_USER_TYPE_PROVIDER,
            ];

            $notificationLog = $this->notificationLogRepo->create($logData);
            $this->smsNotificationLogRepo->create([
                'notification_log_id' => $notificationLog->id,
                // TODO: add sms template message
                'message' => PushNotificationLogs::PUSH_NOTIFICATION_LOG_PROVIDER['new_booking']['message'],
                'status' => SMSNotificationLogs::STATUS_PENDING,
            ]);

            $send = true;
        }

        return $send;
    }
}