<?php

namespace App\Services\Payments\Interfaces;

use App\Services\Payments\Exceptions\InvalidPaymentInitialisationDataException;
use App\Services\Payments\Exceptions\PaymentInitialiserException;

/**
 * Interface PaymentInitialiserInterface
 * @package App\Services\Payments\Interfaces
 */
interface PaymentInitialiserInterface
{
    /**
     * @param array $data
     * @return bool
     */
    public function setInitialisationData(array $data): PaymentInitialiserInterface;

    /**
     * @return bool
     * @throws PaymentInitialiserException
     * @throws InvalidPaymentInitialisationDataException
     */
    public function intialisePayment() : bool;

    /**
     * @return int
     */
    public function getInitialisationId() : string;
}