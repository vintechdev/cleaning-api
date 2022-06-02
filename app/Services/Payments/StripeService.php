<?php

namespace App\Services\Payments;

use App\PaymentDto;
use App\Providermetadatum;
use App\Repository\Eloquent\StripeUserMetadataRepository;
use App\Services\Payments\Exceptions\CreditCardNotSetUpException;
use App\Services\Payments\Exceptions\InvalidPaymentDataException;
use App\Services\Payments\Exceptions\InvalidUserException;
use App\Services\Payments\Exceptions\PaymentAccountNotSetUpException;
use App\Services\Payments\Exceptions\PaymentInitialiserException;
use App\Services\Payments\Exceptions\StripeMetadataUpdateException;
use App\StripeUserMetadata;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Stripe\Account;
use Stripe\AccountLink;
use Stripe\Balance;
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
        $customerId = $this->createCustomerIfNotExists($user);
        return SetupIntent::create([
            'customer' => $customerId
        ])->client_secret;
    }

    /**
     * @param array $data
     * @return bool
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function transferAmount(PaymentDto $payment): bool
    {
        Log::info('Attempting to transfer payment on stripe for user: ' . $payment->getPayer()->getId() . ' and provider ' . $payment->getPayee()->getId());
        $this->validatePaymentIntentData($payment);
        $metadata = $this->metadataRepo->findByUserId($payment->getPayee()->getId());
        if (!$metadata || !$metadata->stripe_connect_account_verified) {
            Log::error('Payment could not be processed as stripe connect is not verified for provider ' . $payment->getPayee()->getId());
            throw new PaymentAccountNotSetUpException('Stripe connect account does not exist or is not verified for the provider');
        }

        $this->validatePaymentMethod($payment->getPayer());

        $stripeAccountId = $metadata->stripe_connect_account_id;
        if (!$stripeAccountId) {
            Log::error('Payment cancelled as stripe connect account does not exist for the user ' . $payment->getPayee()->getId());
            throw new InvalidUserException('Stripe connect account does not exist for this user');
        }

        $intent['amount'] = (int)floor($payment->getAmount() * 100);
        $intent['currency'] = $payment->getCurrency() ? : PaymentDto::PAYMENT_CURRENCY_AUD;
        $intent['payment_method_types'][] = $payment->getPaymentMethodType() ? : PaymentDto::PAYMENT_METHOD_TYPE_CARD;
        $intent['application_fee_amount'] = (int) floor(($payment->getTransferFeePercentage() * ($payment->getAmount() * 100)) / 100);
        $intent['transfer_data']['destination'] = $stripeAccountId;
        $metadata = $this->metadataRepo->findByUserId($payment->getPayer()->getId());
        $intent['customer'] = $metadata->stripe_customer_id;

        if ($payment->getPaymentDescription()) {
            $intent['statement_descriptor'] = $payment->getPaymentDescription();
        }

        if ($payment->getMetadata()) {
            $intent['metadata'] = $payment->getMetadata();
        }

        $paymentIntent = PaymentIntent::create($intent)
            ->confirm(['payment_method' => $metadata->stripe_payment_method_id])
            ->toArray();
        if (isset($paymentIntent['error'])) {
            $error = isset($paymentIntent['error']['message']) ?
                $paymentIntent['error']['message'] :
                'An error occured when initiating a transfer with stripe';

            Log::error('An error occured when initiating a payment with stripe');
            throw new PaymentInitialiserException($error);
        }

        return true;
    }

    /**
     * @param string $successUrl
     * @param string $cancelUrl
     * @param User $user
     * @return Session
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createSession(string $successUrl, string $cancelUrl, User $user): string
    {
        $customerId = $this->createCustomerIfNotExists($user);
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
     * @param User $user
     * @return bool
     * @throws CreditCardNotSetUpException
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function validatePaymentMethod(User $user): bool
    {
        $paymentMethod = $this->retrieveStoredPaymentMethod($user->getId());
        if (!$paymentMethod) {
            Log::error('Credit card not set up for user ' . $user->getId());
            throw new CreditCardNotSetUpException('Customer does not have a credit card added');
        }

        $cvcPass = isset($paymentMethod['card']['checks']['cvc_check']) &&
            ($paymentMethod['card']['checks']['cvc_check'] === 'pass' ||
            $paymentMethod['card']['checks']['cvc_check'] === 'unavailable' ||
            $paymentMethod['card']['checks']['cvc_check'] === 'unchecked');

        $expMonth = str_pad(
            $paymentMethod['card']['exp_month'],
            2,
            '0',
            STR_PAD_LEFT
        );

        if (!$cvcPass ||
            Carbon::createFromFormat(
            'mY',
            $expMonth . $paymentMethod['card']['exp_year']
        )
            ->lessThan(Carbon::now())
        ) {
            Log::error('Credit card has expired for user ' . $user->getId());
            throw new CreditCardNotSetUpException('Customer\'s credit card has expired.');
        }

        return true;
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
        $metadata = $this->metadataRepo->findByUserId($user->getId());

        if ($metadata && $metadata->stripe_connect_account_verified) {
            $loginLink = $this->createAccountLoginLink($user, $refreshUrl);
            if ($loginLink) {
                return ['url' => $loginLink . '#/account'];
            }
        }

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
     * @param string $redirectUrl
     * @return string
     * @throws InvalidUserException
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createAccountLoginLink(User $user, string $redirectUrl): string
    {
        $metadata = $this->metadataRepo->findByUserId($user->getId());

        if (!$metadata || !$metadata->stripe_connect_account_id) {
            throw new InvalidUserException('Stripe account not found for the user.');
        }

        return Account::createLoginLink($metadata->stripe_connect_account_id, [
            'redirect_url' => $redirectUrl
        ])->url;
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
                    'business_type' => 'individual',
                    'individual' => [
                        'email' => $user->email,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name
                    ],
                    'metadata' => [
                        'user_id' => $user->getId()
                    ],
                    'capabilities' => [
                        'transfers' => [
                            'requested' => true,
                        ],
                        'card_payments' => [
                            'requested' => true
                        ]
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
        if ($metadata && $metadata->stripe_connect_account_verified) {
            return true;
        }
        $account = $this->getAccountStatus($user);
        if (
            isset($account['details_submitted']) &&
            $account['details_submitted'] === true
        ) {
            if (isset($account['requirements']['errors']) && count($account['requirements']['errors'])) {
                return  false;
            }

            $metadata->stripe_connect_account_verified = true;
            if (!$metadata->save()) {
                throw new StripeMetadataUpdateException('Stripe user metadata could not be updated');
            }

            $providerMetadata = Providermetadatum::findByProviderId($user->getId());
            if ($providerMetadata) {
                if(!$providerMetadata->setVerified(true)) {
                    throw new StripeMetadataUpdateException('Provider metadata could not be updated');
                }
            }

            return true;
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
     * @param User $user
     * @return array
     * @throws InvalidUserException
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function getAccountBalance(User $user)
    {
        $metadata = $this->metadataRepo->findByUserId($user->getId());

        if (!$metadata || !$metadata->stripe_connect_account_id) {
            throw new InvalidUserException('User does not have stripe account');
        }
        return Balance::retrieve(['stripe_account' => $metadata->stripe_connect_account_id])->toArray();
    }

    /**
     * @param array $data
     * @return bool
     * @throws InvalidPaymentDataException
     */
    private function validatePaymentIntentData(PaymentDto $paymentDto) : bool
    {
        if (!$paymentDto->getAmount()) {
            throw new InvalidPaymentDataException('Payment amount is not set');
        }

        if (!$paymentDto->getTransferFeePercentage()) {
            throw new InvalidPaymentDataException('Transfer fee percentage is not set');
        }

        if (!$paymentDto->getPayee()) {
            throw new InvalidPaymentDataException('Payee is not set');
        }

        if (!$paymentDto->getPayer()) {
            throw new InvalidPaymentDataException('Payee is not set');
        }

        return true;
    }

    /**
     * @param User $user
     * @return string
     * @throws \Stripe\Exception\ApiErrorException
     */
    private function createCustomerIfNotExists(User $user) : string
    {
        $metadata = $this->findOrCreateMetadata($user->getId());
        if (!$metadata->stripe_customer_id) {
            $customerId = Customer::create(
                [
                    'email' => $user->email,
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'metadata' => ['user_id' => $user->getId()]
                ]
            )->id;
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
            $metadata = $this->metadataRepo->create(['user_id' => $userId]);
        }

        return $metadata;
    }
}