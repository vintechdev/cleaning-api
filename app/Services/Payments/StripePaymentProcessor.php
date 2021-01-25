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
use App\User;

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
        return $this->stripeService->transferAmount($this->paymentData);
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