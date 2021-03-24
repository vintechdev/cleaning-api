<?php

namespace App\Services\Payments;

use App\PaymentDto;
use App\Services\Payments\Exceptions\CreditCardNotSetUpException;
use App\Services\Payments\Exceptions\InvalidPaymentDataException;
use App\Services\Payments\Exceptions\InvalidUserException;
use App\Services\Payments\Exceptions\PaymentAccountNotSetUpException;
use App\Services\Payments\Exceptions\PaymentProcessorException;
use App\Services\Payments\Interfaces\PaymentProcessorInterface;
use App\Services\Payments\Interfaces\PaymentUserValidatorInterface;
use App\Setting;
use App\User;
use Illuminate\Support\Facades\Log;

/**
 * Class StripePaymentProcessor
 * @package App\Services\Payments
 */
class StripePaymentProcessor implements PaymentProcessorInterface, PaymentUserValidatorInterface
{
    /**
     * @var PaymentDto
     */
    private $paymentData;

    /**
     * @var StripeService
     */
    private $stripeService;

    /**
     * StripePaymentProcessor constructor.
     * @param StripeService $stripeService
     */
    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * @return boolean
     * @throws PaymentProcessorException
     * @throws InvalidUserException
     * @throws InvalidPaymentDataException
     * @throws PaymentAccountNotSetUpException
     * @throws CreditCardNotSetUpException
     */
    public function process(): bool
    {
        $transferFeePercent = $this
            ->paymentData
            ->getTransferFeePercentage() ? : Setting::getStripeServiceFeePercentage();

        $this->paymentData->setTransferFeePercentage($transferFeePercent);
        Log::info('Starting to process payment for user ' . $this->paymentData->getPayer()->getId() . ' to provider ' . $this->paymentData->getPayee()->getId());
        if ($this->stripeService->transferAmount($this->paymentData)) {
            Log::info('Payment successful');
            return true;
        }

        Log::error('Payment failed');
        return false;
    }

    /**
     * @param PaymentDto $payment
     * @return $this|StripePaymentProcessor
     */
    public function setPaymentData(PaymentDto $payment)
    {
        $this->paymentData = $payment;
        return $this;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isUsersCardValid(User $user): bool
    {
        try {
            return $this->stripeService->validatePaymentMethod($user);
        } catch (\Exception $exception) {
            return false;
        }
    }
}