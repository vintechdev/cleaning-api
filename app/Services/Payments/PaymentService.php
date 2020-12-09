<?php

namespace App\Services\Payments;

use App\Repository\Eloquent\PaymentGatewayRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PaymentService
 * @package App\Services
 */
class PaymentService
{
    /**
     * @var PaymentGatewayRepository
     */
    private $paymentGatewayRepo;

    /**
     * @param PaymentGatewayRepository $paymentGatewayRepository
     */
    public function __constructor(PaymentGatewayRepository $paymentGatewayRepository)
    {
        $this->paymentGatewayRepo = $paymentGatewayRepository;
    }

    /**
     * @return Collection
     */
    public function getAllActivePaymentGateWays() : Collection
    {
        return $this->paymentGatewayRepo->getAllActive();
    }
}