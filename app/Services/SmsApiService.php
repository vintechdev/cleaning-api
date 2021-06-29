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
        $headers = [
          'Authorization' => 'Bearer '. $this->smsApiKey
        ];

        $client = new Client([
            'headers' => $headers
        ]);


        $url = $this->smsApiUrl. '?recipient='.  Str::replaceFirst("+", "", $this->mobileNumber);
        $url .= '&sender_id=' . $this->smsSenderId;
        $url .= '&message='.  urlencode($this->message);

        try {
            
            $request = $client->request('POST', $url);

            if (in_array($request->getStatusCode(), [200, 201])) {
                return json_decode($request->getBody(), true);
            }

            return [
               'status' => 'error',
               'message' => 'Error while sending message'
            ];

        } catch (RequestException $e) {
          
        } catch (ClientException $e) {

        } catch (\Exception $e) {
            return [
               'status' => 'error',
               'message' => $e->getMessage() 
            ];
        }

        return null;
    }

}