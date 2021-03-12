<?php


namespace App\Services\Payments\Interfaces;

use App\PaymentDto;
use App\Services\Payments\Exceptions\CreditCardNotSetUpException;
use App\Services\Payments\Exceptions\InvalidPaymentDataException;
use App\Services\Payments\Exceptions\InvalidUserException;
use App\Services\Payments\Exceptions\PaymentAccountNotSetUpException;
use App\Services\Payments\Exceptions\PaymentProcessorException;

/**
 * Interface PaymentProcessorInterface
 * @package App\Services\Payments\Interfaces
 */
interface PaymentProcessorInterface
{
    /**
     * @return boolean
     * @throws PaymentProcessorException
     * @throws InvalidUserException
     * @throws InvalidPaymentDataException
     * @throws PaymentAccountNotSetUpException
     * @throws CreditCardNotSetUpException
     */
    public function process() : bool;

    /**
     * @param PaymentDto $payment
     * @return $this
     */
    public function setPaymentData(PaymentDto $payment);
}
