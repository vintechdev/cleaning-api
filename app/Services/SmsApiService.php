<?php

namespace App\Services;

use GuzzleHttp\Client;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SmsApiService
{

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $sender;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string;
     */
    private $message;

    /*
     * @var string;
     */
    private $mobileNumber;

    /*
    * @var Boolean
    */
    private $enabled;

    /**
     * SmsApiService constructor.
     */
    public function __construct()
    {
        $this->setProperties();
    }

    private function setProperties(): self
    {
        $smsConfig = Config::get('services.sms', []);

        foreach ($smsConfig as $configKey => $value) {
            $this->{Str::camel($configKey)} = $value;
        }

        return $this;
    }

    public function setMessage(string $message = ""): self
    {
        $this->message = $message;

        return $this;
    }

    public function setMobileNumber(string $mobileNumber = ""): self
    {
        $this->mobileNumber = $mobileNumber;

        return $this;
    }

    private function checkPropertyValues()
    {
        if (!$this->enabled) {
            Log::info('sms service is not enabled.');
            return false;
        } elseif (empty($this->url)) {
            Log::error('sms service url is missing.');
            return false;
        } elseif (empty($this->sender)) {
            Log::error('sms service sender id is missing.');
            return false;
        } elseif (empty($this->mobileNumber)) {
            Log::error('sms service receiver mobile number is missing.');
            return false;
        }

        return true;
    }

    public function send()
    {
        if (!$this->checkPropertyValues()) {
            return false;
        }

        $client = new Client();
        try {
            $requestData = [
                'action' => 'send-sms',
                'api_key' => $this->apiKey,
                'to' => Str::replaceFirst("+", "", $this->mobileNumber),
                'from' => $this->sender,
                'sms' => urlencode($this->message),
            ];

            $request = $client->request('GET', $this->url, ['query' => $requestData]);
            $result = json_decode($request->getBody(), true);

            if (in_array(strtolower($result["code"]) , ["ok", true, "success"])) {
                return true;
            }

            Log::info('sms sent to: ' . $this->mobileNumber, $result);
            return false;
        } catch (Exception $exception) {
            Log::error("Unable to send sms: " . $exception->getMessage(), (array)$exception);
            return false;
        } catch (GuzzleException $exception) {
            Log::error("Unable to send sms: " . $exception->getMessage(), (array)$exception);
            return false;
        }
    }

}