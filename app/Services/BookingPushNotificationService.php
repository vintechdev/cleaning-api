<?php

namespace App\Services;

use App\NotificationLog;
use App\Booking;
use App\PushNotificationLogs;
use App\Repository\BookingServiceRepository;
use App\Repository\Eloquent\NotificationLogRepository;
use App\Repository\UserRepository;
use App\Repository\BookingReqestProviderRepository;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * Class BookingEmailNotificationService
 * @package App\Services
 */
class BookingPushNotificationService extends AbstractBookingNotificationService
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
    public function __construct(NotificationLogRepository $notificationLogRepository){
        parent::__construct($notificationLogRepository);
        $this->bookingservicerepo = app(BookingServiceRepository::class);
        $this->userRepo = app(UserRepository::class);
        $this->bookingrequestprovider = app(BookingReqestProviderRepository::class);
    }
    protected function sendNotification(): bool
    {
        if($this->sendUserPushNotification()){
            return true;
        }

        return false;
    }

    protected function getNotificationType(): string
    {
        return NotificationLog::NOTIFICATION_TYPE_BOOKING_CREATED_PUSH;
    }

    public function sendUserPushNotification()
    {
        $notificationSetting = $this->userRepo->getUserNotification(Auth::user()->id,self::TRANSACTION_SETTING_ID);

        if (!$notificationSetting || ($notificationSetting && !$notificationSetting['push'])) {
            return false;
        }

        $logData = [
            'event_type' => $this->getNotificationType(),
            'user_id' => Auth::user()->id,
            'booking_id' => $this->booking->id,
            'user_type' => NotificationLog::NOTIFICATION_LOG_USER_TYPE_USER,
        ];

        $notificationLog = $this->notificationLogRepo->create($logData);

        PushNotificationLogs::query()->create([
            'notification_log_id' => $notificationLog->id,
            'title' => PushNotificationLogs::PUSH_NOTIFICATION_LOG_USER['new_booking']['title'],
            'message' => PushNotificationLogs::PUSH_NOTIFICATION_LOG_USER['new_booking']['message'],
            'status' => PushNotificationLogs::STATUS_UNREAD,
        ]);

        return true;
    }
}