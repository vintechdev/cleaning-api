<?php

namespace App\Services;

use App\Notification;
use App\NotificationLog;
use App\PushNotificationLogs;
use App\Repository\Eloquent\NotificationLogRepository;
use App\Repository\UserNotificationRepository;
use App\Repository\BookingReqestProviderRepository;
use App\Bookingstatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class BookingStatusChangePushNotificationService
 * @package App\Services
 */
class BookingStatusChangePushNotificationService extends AbstractBookingNotificationService
{

   protected $bookingrequestprovider;

    /**
     * @var UserNotificationRepository
     */
    private $userNotificationRepo;

    public function __construct(
        NotificationLogRepository $notificationLogRepository,
        BookingReqestProviderRepository $bookingrequestprovider
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
        $countPendingProviders = 0;

        if ($this->booking->getStatus() === Bookingstatus::BOOKING_STATUS_REJECTED) {
            $countPendingProviders = $this->bookingrequestprovider->getCountWithStatuses([
                Bookingstatus::BOOKING_STATUS_PENDING
            ], $this->booking->getId());
        }

        /** Notify only to provider if still provider are having pending status **/
        if ($countPendingProviders > 0) {
            $this->createNotificationLog(Auth::user()->id,
                $this->getProviderNotificationTitle($status),
                $this->getProviderNotificationMessage($status)
            );
            return true;
        }

        $notificationSetting = $this->getNotificationSetting($this->booking->getUserId());

        if (!$notificationSetting || ($notificationSetting && $notificationSetting->allow_push != 0 && (!$notificationSetting->push))) {
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

    protected function sendProviderPushNotfication($status) {
        if(in_array($this->booking->getStatus(),[
            Bookingstatus::BOOKING_STATUS_ACCEPTED,
            Bookingstatus::BOOKING_STATUS_ARRIVED,
            Bookingstatus::BOOKING_STATUS_COMPLETED,
            Bookingstatus::BOOKING_STATUS_APPROVED,
            Bookingstatus::BOOKING_STATUS_PENDING,
        ])) {
            $bookingProviders = $this->bookingrequestprovider
                ->getBookingAccptedProvidersDetails($this->booking->getId());

            foreach ($bookingProviders as $k => $provider) {
                $this->createNotificationLog($provider['provider_user_id'],
                    $this->getProviderNotificationTitle($status),
                    $this->getProviderNotificationMessage($status)
                );
            }
        } else if ($this->booking->getStatus() === Bookingstatus::BOOKING_STATUS_REJECTED) {
            // Notify last provider
            $this->createNotificationLog(Auth::user()->id,
                $this->getProviderNotificationTitle($status),
                $this->getProviderNotificationMessage($status)
            );
        } else if ($this->booking->getStatus() === Bookingstatus::BOOKING_STATUS_CANCELLED
            && $this->booking->getUserId() !== Auth::user()->id) {
            $this->createNotificationLog(Auth::user()->id,
                $this->getProviderNotificationTitle($status),
                $this->getProviderNotificationMessage($status)
            );
        } else if ($this->booking->getStatus() === Bookingstatus::BOOKING_STATUS_CANCELLED
            && $this->booking->getUserId() === Auth::user()->id) {
            $bookingProviders = $this->bookingrequestprovider->getBookingProvidersData($this->booking->id);

            if(count($bookingProviders) === 0){
                return false;
            }

            foreach ($bookingProviders as $k => $provider) {
                $this->createNotificationLog($provider['provider_user_id'],
                    $this->getProviderNotificationTitle($status),
                    $this->getProviderNotificationMessage($status)
                );
            }
        }

        return true;
    }

    private function getProviderNotificationTitle($status): string {
       return PushNotificationLogs::PUSH_NOTIFICATION_LOG_PROVIDER['booking_status_update']['title'];
    }

    private function getProviderNotificationMessage($status): string {
        return Str::replaceFirst('{statusName}', $status, PushNotificationLogs::PUSH_NOTIFICATION_LOG_PROVIDER['booking_status_update']['message']);
    }

    private function getNotificationSetting($userId) {
        return $notificationSetting = $this->userNotificationRepo
            ->getUserNotificationType($userId, Notification::BOOKING_UPDATES);
    }

    private function createNotificationLog($userId, $title , $message) {
        $notificationSetting = $this->getNotificationSetting($userId);

        if (!$notificationSetting || ($notificationSetting && $notificationSetting->push === 0)) {
            return;
        }

        $logData = [
            'event_type' => $this->getNotificationType(),
            'user_id' => $userId,
            'booking_id' => $this->booking->getId(),
            'user_type' => NotificationLog::NOTIFICATION_LOG_USER_TYPE_PROVIDER,
        ];

        $notificationLog = $this->notificationLogRepo->create($logData);
        PushNotificationLogs::query()->create([
            'notification_log_id' => $notificationLog->id,
            'title' => $title,
            'message' => $message,
            'status' => PushNotificationLogs::STATUS_UNREAD,
        ]);
    }
}
