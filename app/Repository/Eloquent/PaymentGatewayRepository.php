<?php


namespace App\Repository\Eloquent;

use App\PaymentGateway;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PaymentGatewayRepository
 * @package App\Repository\Eloquent
 */
class PaymentGatewayRepository extends AbstractBaseRepository
{
    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return PaymentGateway::class;
    }

    /**
     * @return Collection
     */
    public function getAllActive(): Collection
    {
        return $this->getModelClass()::where(['is_active' => true]);
    }
}

