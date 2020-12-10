<?php


namespace App\Services\Payments\Interfaces;

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
     */
    public function process() : bool;
}
