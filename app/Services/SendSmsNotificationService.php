<?php
namespace App\Services;

use App\Repository\SmsNotificationLogRepo;
use App\Setting;
use Illuminate\Support\Facades\Log;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;


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
        $settings = Setting::query()->where('type', 'sms')
            ->get()
            ->pluck('value', 'key')
            ->toArray();

        if (count($settings) < 3) {
            Log::error("Sms setting keys missing in setting table.");
            return;
        }

       $this->smsApiService->setSmsApiUrl($settings['sms_api_url'])
       ->setSmsApiKey($settings['sms_api_key'])
       ->setSmsSenderId($settings['sms_sender_id']);

        $data = $this->getAllPendingSms();

        if ($data->count() === 0) {
            Log::error('No pending available');
            return;
        }

        $phoneNumberUtil = PhoneNumberUtil::getInstance();

        $mobileNumber = "";
        $phoneNumber = null;
        foreach ($data as $smsLog) {
            if (!$smsLog->notificationlogs->user->mobile_number || empty($smsLog->notificationlogs->user->mobile_number)) {
                continue;
            }

            if ($mobileNumber != $smsLog->notificationlogs->user->mobile_number) {
                $phoneUtilObject = $phoneNumberUtil->parse($smsLog->notificationlogs->user->mobile_number, 'AU');
                $phoneNumber = $phoneNumberUtil->format($phoneUtilObject, PhoneNumberFormat::E164);
                $mobileNumber = $smsLog->notificationlogs->user->mobile_number;
            }

            if (!$phoneNumber) {
                continue;
            }

            $response = $this->smsApiService->setMessage($smsLog->message)
                ->setMobileNumber($phoneNumber)
                ->send();

            $status = 'sent';
            if (isset($response) && $response['code'] !== "ok") {
                $status = "failed";
            }

            $smsLog->update([
                'status' =>  $status,
                'response' => ($status === 'failed') ? $response : null
            ]);
        }
    }
}