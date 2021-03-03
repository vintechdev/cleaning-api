<?php

namespace App\Services;

use App\Notification;
use App\NotificationLog;
use App\PushNotificationLogs;
use App\Repository\Eloquent\NotificationLogRepository;
use App\Repository\UserNotificationRepository;
use App\Repository\BookingReqestProviderRepository;
use App\Repository\UserBadgeReviewRepository;
use App\Bookingstatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class BookingStatusChangeEmailNotificationService
 * @package App\Services
 */
class BookingStatusChangePushNotificationService extends AbstractBookingNotificationService
{

    protected $bookingservicerepo;
    protected $bookingrequestprovider;

    /**
     * @var UserNotificationRepository
     */
    private $userNotificationRepo;

    public function __construct(
        NotificationLogRepository $notificationLogRepository,
        BookingReqestProviderRepository $bookingrequestprovider,
        UserBadgeReviewRepository $userbadge
    ) {
        parent::__construct($notificationLogRepository);

        $this->bookingrequestprovider = $bookingrequestprovider;
        $this->userNotificationRepo =  app(UserNotificationRepository::class);
    }
    

    protected function sendNotification(): bool
    {
        $status =  $this->getBookingStatusByBooking();

        if ($this->sendUserPushNotification($status)) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    protected function getNotificationType(): string
    {
        return NotificationLog::NOTIFICATION_TYPE_BOOKING_STATUS_CHANGE_PUSH;
    }

    private function getBookingStatusByBooking(): string {
        return Bookingstatus::getStatusNameById($this->booking->getStatus());
    }

    public function sendUserPushNotification($status)
    {
        $notificationSetting = $this->userNotificationRepo
            ->getUserNotificationType($this->booking->getUserId(), Notification::BOOKING_UPDATES);

        if ($notificationSetting->allow_push != 0 && (!$notificationSetting->push)) {
            return false;
        }

        $logData = [
            'event_type' => $this->getNotificationType(),
            'user_id' => $this->booking->getUserId(),
            'booking_id' => $this->booking->getId(),
            'user_type' => NotificationLog::NOTIFICATION_LOG_USER_TYPE_USER,
        ];

        $notificationLog = $this->notificationLogRepo->create($logData);

        $statusMessage = Str::replaceFirst('{statusName}', $status, PushNotificationLogs::PUSH_NOTIFICATION_LOG_USER['booking_status_update']['message']);

        // TODO: can be change later in repo
        PushNotificationLogs::query()->create([
            'notification_log_id' => $notificationLog->id,
            'title' => PushNotificationLogs::PUSH_NOTIFICATION_LOG_USER['booking_status_update']['title'],
            'message' => $statusMessage,
            'status' => PushNotificationLogs::STATUS_UNREAD,
        ]);

        $this->sendProviderPushNotfication($status);

        return true;
    }

    protected function sendProviderPushNotfication($status){
        $bookingProviders = $this->bookingrequestprovider->getBookingAccptedProvidersDetails($this->booking->getId());

        if(count($bookingProviders) === 0){
            return false;
        }

        $title = PushNotificationLogs::PUSH_NOTIFICATION_LOG_PROVIDER['booking_status_update']['title'];
        $statusMessage = Str::replaceFirst('{statusName}', $status, PushNotificationLogs::PUSH_NOTIFICATION_LOG_PROVIDER['booking_status_update']['message']);

        $send = false ;
        foreach ($bookingProviders as $k => $provider) {
            $notificationSetting = $this->userNotificationRepo
                ->getUserNotificationType($provider['provider_user_id'], Notification::BOOKING_UPDATES);

            if (!$notificationSetting || ($notificationSetting && $notificationSetting->push === 0)) {
                continue;
            }


            $logData = [
                'event_type' => $this->getNotificationType(),
                'user_id' => $provider['provider_user_id'],
                'booking_id' => $this->booking->id,
                'user_type' => NotificationLog::NOTIFICATION_LOG_USER_TYPE_PROVIDER,
            ];

            $notificationLog = $this->notificationLogRepo->create($logData);
            PushNotificationLogs::query()->create([
                'notification_log_id' => $notificationLog->id,
                'title' => $title,
                'message' => $statusMessage,
                'status' => PushNotificationLogs::STATUS_UNREAD,
            ]);

            $send = true;
        }

        return $send;
    }
}
