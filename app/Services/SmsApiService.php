<?php
namespace App\Services;

use GuzzleHttp\Client;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Boolean;

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
    private $password;

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

        foreach ($smsConfig as $configKey => $value){
            $this->{$configKey} = $value;
        }

        return $this;
    }

    public function setMessage(string $message = "") : self {
        $this->message = $message;

        return $this;
    }

    public function setMobileNumber(string $mobileNumber = "") : self {
        $this->mobileNumber = $mobileNumber;

        return $this;
    }

    private function checkPropertyValues() {
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

    public function send(): Boolean {
        if (!$this->checkPropertyValues()) {
            return false;
        }

        $client = new Client();
        try {

            $response = $client->post($this->url, [
                'verify'    =>  false,
                'form_params' => [
                    'username' => $this->sender,
                    'password' => $this->password,
                    'message' => $this->message,
                    'phone' => $this->mobileNumber,
                ],
            ]);

            $response = json_decode($response->getBody(), true);
            Log::info('sms sent to: '.  $this->mobileNumber, $response);

        } catch (Exception $exception) {
            Log::error("Unable to send sms: " . $exception->getMessage(), (array) $exception);
            return false;
        }

        return true;
    }

}