<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Str;

class SmsApiService
{

    /**
     * @var string
     */
    private $smsApiUrl;

    /**
     * @var string
     */
    private $smsSenderId;

    /**
     * @var string
     */
    private $smsApiKey;

    /**
     * @var string;
     */
    private $message;

    /*
     * @var string;
     */
    private $mobileNumber;

    public function setSmsApiUrl($value = ""): self {
        $this->smsApiUrl = $value;

        return $this;
    }

    public function setSmsSenderId($value = ""): self {
        $this->smsSenderId = $value;

        return $this;
    }

    public function setSmsApiKey($value = ""): self {
        $this->smsApiKey = $value;

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

    public function send()
    {
        $client = new Client();

        $requestData = [
            'action' => 'send-sms',
            'api_key' => $this->smsApiKey,
            'to' => Str::replaceFirst("+", "", $this->mobileNumber),
            'from' => $this->smsSenderId,
            'sms' => urlencode($this->message),
        ];

        $request = $client->request('GET', $this->smsApiUrl, ['query' => $requestData]);

        return json_decode($request->getBody(), true);
    }

}