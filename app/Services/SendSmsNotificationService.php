<?php
namespace App\Services;

use App\Repository\SmsNotificationLogRepo;

class SendSmsNotificationService
{
    /**
     * @var SmsApiService
     */
    private $smsApiService;
    /**
     * @var SmsNotificationLogRepo
     */
    private $smsNotificationLogRepo;

    /**
     * SmsNotificationService constructor.
     * @param SmsApiService $smsApiService
     * @param SmsNotificationLogRepo $smsNotificationLogRepo
     */
    public function __construct(SmsApiService $smsApiService, SmsNotificationLogRepo $smsNotificationLogRepo)
    {
        $this->smsApiService = $smsApiService;
        $this->smsNotificationLogRepo = $smsNotificationLogRepo;
    }

    public function getAllPendingSms()
    {
        return $this->smsNotificationLogRepo->getAllPendingSmsList();
    }

    public function sendSms()
    {
        if (!$data = $this->getAllPendingSms()) {
            return;
        }

        foreach ($data as $smsLog) {
            $response = $this->smsApiService->setMessage($smsLog->message)
                ->setMobileNumber($smsLog->notificationlogs->user->mobile_number)
                ->send();

            $status = $response ? 'sent' : 'failed';

            $smsLog->update([
                'status' =>  $status
            ]);
        }
    }

}