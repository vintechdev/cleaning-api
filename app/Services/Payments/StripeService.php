<?php

namespace App\Services\Payments;

use App\Repository\Eloquent\StripeUserMetadataRepository;
use App\Services\Payments\Exceptions\StripeMetadataUpdateException;
use App\User;
use Carbon\Carbon;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\SetupIntent;
use Stripe\Stripe;

/**
 * Class StripePaymentService
 * @package App\Services\Payments
 */
class StripeService
{
    /**
     * @var StripeUserMetadataRepository
     */
    private $metadataRepo;

    /**
     * StripeService constructor.
     * @param StripeUserMetadataRepository $metadataRepository
     */
    public function __construct(StripeUserMetadataRepository $metadataRepository, string $apiSecret)
    {
        $this->metadataRepo = $metadataRepository;
        Stripe::setApiKey($apiSecret);
    }

    /**
     * @param User $user
     * @return string|null
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createPaymentMethodSetupIntent(User $user)
    {
        $customerId = $this->createCustomerIfNotExists($user->getId());
        return SetupIntent::create([
            'customer' => $customerId
        ])->client_secret;
    }

    /**
     * @param array $data
     * @return int
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createPaymentIntent(array $data) : string
    {
        if (!$this->isValidPaymentIntentData($data)) {
            throw new \InvalidArgumentException('Invalid data received for initialising payment');
        }

        $customerId = $this->createCustomerIfNotExists($data['user_id']);
        unset($data['user_id']);

        $data['customer'] = $customerId;
        return PaymentIntent::create($data)->client_secret;
    }

    /**
     * @param string $successUrl
     * @param string $cancelUrl
     * @param int $userId
     * @return Session
     * @throws \Stripe\Exception\ApiErrorException
     * @throws StripeMetadataUpdateException
     */
    public function createSession(string $successUrl, string $cancelUrl, int $userId) : string
    {
        $customerId = $this->createCustomerIfNotExists($userId);
        $query = parse_url($successUrl, PHP_URL_QUERY);

        if ($query) {
            $successUrl .= '&session_id={CHECKOUT_SESSION_ID}';
        } else {
            $successUrl .= '?session_id={CHECKOUT_SESSION_ID}';
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'setup',
            'customer' => $customerId,
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ]);
        return $session->id;
    }

    /**
     * @param int $userId
     * @return array
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function retrieveStoredPaymentMethod(int $userId) : array
    {
        $metadata = $this->metadataRepo->findByUserId($userId);

        if (!$metadata || !$metadata->stripe_payment_method_id) {
            return [];
        }
        $customerId = $metadata->stripe_customer_id;

        return PaymentMethod::all(['customer' => $customerId, 'type' => 'card'])
            ->retrieve($metadata->stripe_payment_method_id)
            ->toArray();
    }

    /**
     * @param string $paymentMethodId
     * @param int $userId
     * @return array
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function retrieveStoredPaymentMethodByPaymentMethodId(string $paymentMethodId, int $userId)
    {
        $metadata = $this->metadataRepo->findByUserId($userId);
        if (!$metadata) {
            return [];
        }
        $customerId = $metadata->stripe_customer_id;
        return PaymentMethod::all(['customer' => $customerId, 'type' => 'card'])
            ->retrieve($paymentMethodId)
            ->toArray();
    }

    /**
     * @param string $sessionId
     * @param User $user
     * @return bool
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function associatePaymentMethod(string $paymentMethodId, User $user) : bool
    {
        $paymentMethods = $this->retrieveStoredPaymentMethodByPaymentMethodId($paymentMethodId, $user->getId());
        if (!$paymentMethods) {
            throw new \RuntimeException('Payment method id does not belong to the user.');
        }

        $metadata = $this->metadataRepo->findByUserId($user->getId());

        if (!$metadata) {
            throw new StripeMetadataUpdateException('Metadata not found for user');
        }

        $metadata->stripe_payment_method_id = $paymentMethodId;
        return $metadata->save();
    }

    /**
     * @param array $data
     * @return bool
     */
    private function isValidPaymentIntentData(array $data) : bool
    {
        //TODO: Add validation
        return true;
    }

    /**
     * @param string $userId
     * @return string
     * @throws \Stripe\Exception\ApiErrorException
     * @throws \RuntimeException
     */
    private function createCustomerIfNotExists(string $userId) : string
    {
        $metadata = $this->metadataRepo->findByUserId($userId);

        if (!$metadata) {
            $customerId = Customer::create()->id;
            if (!$customerId) {
                throw new \RuntimeException('Unable to create stripe customer');
            }

            $this->metadataRepo->create(['user_id' => $userId, 'stripe_customer_id' => $customerId]);
            return $customerId;
        }

        return $metadata->stripe_customer_id;
    }
}