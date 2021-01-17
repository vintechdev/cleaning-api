<?php

namespace App\Services\Payments;

use App\Repository\Eloquent\StripeUserMetadataRepository;
use App\Services\Payments\Exceptions\InvalidUserException;
use App\Services\Payments\Exceptions\StripeMetadataUpdateException;
use App\StripeUserMetadata;
use App\User;
use Carbon\Carbon;
use Stripe\Account;
use Stripe\AccountLink;
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
    public function createPaymentIntent(array $data): string
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
    public function createSession(string $successUrl, string $cancelUrl, int $userId): string
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
    public function retrieveStoredPaymentMethod(int $userId): array
    {
        $metadata = $this->metadataRepo->findByUserId($userId);

        if (!$metadata || !$metadata->stripe_payment_method_id) {
            return [];
        }

        return $this
            ->retrieveStoredPaymentMethodByPaymentMethodId($metadata->stripe_payment_method_id, $userId)
            ->toArray();
    }

    /**
     * @param string $paymentMethodId
     * @param int $userId
     * @return array|\Stripe\StripeObject
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
            ->retrieve($paymentMethodId);
    }

    /**
     * @param string $sessionId
     * @param User $user
     * @return bool
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function associatePaymentMethod(string $paymentMethodId, User $user): bool
    {
        $paymentMethods = $this->retrieveStoredPaymentMethodByPaymentMethodId($paymentMethodId, $user->getId());
        if (!$paymentMethods->count()) {
            throw new \RuntimeException('Payment method id does not belong to the user.');
        }

        $metadata = $this->metadataRepo->findByUserId($user->getId());

        if (!$metadata) {
            throw new StripeMetadataUpdateException('Metadata not found for user');
        }

        $existingPaymentMethod = $metadata->stripe_payment_method_id;
        $metadata->stripe_payment_method_id = $paymentMethodId;
        if ($metadata->save()) {
            //TodO: Remove existing payment method
            return true;
            // Remove exsiting payment method from customer
//            if ($existingPaymentMethod) {
//                $paymentMethod = $this->retrieveStoredPaymentMethodByPaymentMethodId($existingPaymentMethod, $user->getId());
//                if ($paymentMethod->count()) {
//                    $paymentMethod->getObject()->detach();
//                }
//            }
        }

        return false;
    }

    /**
     * @param User $user
     * @param string $returnUrl
     * @param string $refreshUrl
     * @return array
     * @throws InvalidUserException
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createAccountLink(User $user, string $returnUrl, string $refreshUrl): array
    {
        $accountId = $this->findOrCreateStripeConnectAccount($user);
        return AccountLink::create([
            'account' => $accountId,
            'refresh_url' => $refreshUrl,
            'return_url' => $returnUrl,
            'type' => 'account_onboarding',
        ])->toArray();
    }

    /**
     * @param User $user
     * @return string
     * @throws InvalidUserException
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function findOrCreateStripeConnectAccount(User $user): string
    {
        if (!$user->isProvider()) {
            throw new InvalidUserException('Stripe connect account can only be created for providers');
        }

        $metadata = $this->findOrCreateMetadata($user->getId());

        if (!$metadata->stripe_connect_account_id) {
            $account = Account::create(
                [
                    'country' => 'AU',
                    'type' => 'express',
                    'capabilities' => [
                        'transfers' => [
                            'requested' => true,
                        ],
                    ]
                ]
            );

            if (!$account->id) {
                throw new \RuntimeException('Unable to create stripe connect account');
            }
            $metadata->stripe_connect_account_id = $account->id;

            if (!$metadata->save()) {
                throw new \RuntimeException('Unable to save stripe connect account');
            }
        }

        return $metadata->stripe_connect_account_id;
    }

    /**
     * @param User $user
     * @return bool
     * @throws InvalidUserException
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function verifyStripeConnectedAccount(User $user)
    {
        $metadata = $this->metadataRepo->findByUserId($user->getId());
        if ($metadata->stripe_connect_account_verified) {
            return true;
        }
        $account = $this->getAccountStatus($user);
        if (isset($account['details_submitted']) === true) {
            $metadata->stripe_connect_account_verified = true;
            if (!$metadata->save()) {
                throw new StripeMetadataUpdateException('Stripe user metadata could not be updated');
            }
        }

        return false;
    }

    /**
     * @param User $user
     * @return array
     * @throws InvalidUserException
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function getAccountStatus(User $user): array
    {
        $metadata = $this->metadataRepo->findByUserId($user->getId());

        if (!$metadata || !$metadata->stripe_connect_account_id) {
            throw new InvalidUserException('User does not have stripe account');
        }

        $account = Account::retrieve(['id' => $metadata->stripe_connect_account_id]);
        return $account->toArray();
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
        $metadata = $this->findOrCreateMetadata($userId);
        if (!$metadata->stripe_customer_id) {
            $customerId = Customer::create()->id;
            if (!$customerId) {
                throw new \RuntimeException('Unable to create stripe customer');
            }

            $metadata->stripe_customer_id = $customerId;

            if (!$metadata->save()) {
                throw new \RuntimeException('Unable to save stripe customer');
            }
        }

        return $metadata->stripe_customer_id;
    }

    private function findOrCreateMetadata(string $userId): StripeUserMetadata
    {
        $metadata = $this->metadataRepo->findByUserId($userId);
        if (!$metadata) {
            $this->metadataRepo->create(['user_id' => $userId]);
        }

        return $metadata;
    }
}