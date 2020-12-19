<?php

namespace App\Services\Payments;

use App\Services\Payments\Factories\PaymentInitialiserFactory;

/**
 * Class PaymentIntitialisationService
 * @package App\Services\Payments
 */
class PaymentIntitialisationService
{
    /**
     * @var PaymentInitialiserFactory
     */
    private $initialiserFactory;

    public function __construct(PaymentInitialiserFactory $initialiserFactory)
    {
        $this->initialiserFactory = $initialiserFactory;
    }

    /**
     * @param array $data
     * @return int
     * @throws \InvalidArgumentException
     */
    public function initialisePayment(int $paymentGatewayId, array $data) : string
    {
        $initialiser = $this->initialiserFactory->create($paymentGatewayId);
        $initialiser
            ->setInitialisationData($data)
            ->intialisePayment();

        return $initialiser->getInitialisationId();
    }
}