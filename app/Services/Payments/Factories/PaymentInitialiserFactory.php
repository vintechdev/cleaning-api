<?php

namespace App\Services\Payments\Factories;

use App\PaymentGateway;
use App\Services\Payments\Interfaces\PaymentInitialiserInterface;
use App\Services\Payments\StripePaymentProcessor;

/**
 * Class PaymentInitialiserFactory
 * @package App\Services\Payments\Factories
 */
class PaymentInitialiserFactory
{
    /**
     * @param int $paymentGatewayId
     * @return PaymentInitialiserInterface
     * @throws \InvalidArgumentException
     */
    public function create(int $paymentGatewayId)
    {
        if ($paymentGatewayId !== PaymentGateway::STRIPE_PAYMENT_GATEWAY) {
            throw new \InvalidArgumentException('Invalid Payment gateway id received');
        }

        return app()->get(StripePaymentProcessor::class);
    }
}