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

        $pendingSms = $this->getAllPendingSms();

        if ($pendingSms->count() === 0) {
            Log::error('No pending available');
            return;
        }

        $data = $pendingSms->groupBy('notificationlogs.user.mobile_number');
       

       foreach ($data as $mobileNumber => $smsLogs) {
            if (!$mobileNumber || empty($mobileNumber)) {
                continue;
            }

            try {
                $phoneUtil  = PhoneNumberUtil::getInstance();
                $phoneUtilObject = $phoneUtil->parse((string) $mobileNumber, "AU");
            } catch (\libphonenumber\NumberParseException $e) {
                $message = "Mobile number parsing error - $mobileNumber";
                $this->updateFailedSmsLog($smsLogs, $message);
                continue;
            }

            $isValid = $phoneUtil->isValidNumber($phoneUtilObject);
            
            if ($isValid === false){
                $this->updateFailedSmsLog($smsLogs, "Invalid Mobile number  - $mobileNumber");
                continue;
            }

            $phoneNumber = $phoneUtil->format($phoneUtilObject, PhoneNumberFormat::E164);
            
            $this->smsApiService->setMobileNumber($phoneNumber);

            foreach ($smsLogs as $smsLog) {

                $response = $this->smsApiService->setMessage($smsLog->message)->send();
                $status = 'sent';

                if (empty($response) || ($response && isset($response['status']) && $response['status'] !== 'success')) {
                    $status = "failed";
                }

                $smsLog->update([
                    'status' =>  $status,
                    'response' => ($status === 'failed') ? $response : $response['data']
                ]);
            }
        }
    }

    public function updateFailedSmsLog($smsLogs, $message) {
        foreach ($smsLogs as $smsLog) {
            $this->updateLog($smsLog, ['code' => 'invalid', 'message' => $message], 'failed');
        }
    }

    private function updateLog($smsLog, $response, $status) {
        $smsLog->update([
            'status' =>  $status,
            'response' => $response,
        ]);
    }
}