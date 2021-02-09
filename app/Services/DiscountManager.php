<?php

namespace App\Services;

use App\Discounts;
use App\Repository\Eloquent\DiscountRepository;

/**
 * Class DiscountManager
 * @package App\Services
 */
class DiscountManager
{
    const DISCOUNT_TYPE = 'discount_type';
    const DISCOUNT_VALUE = 'discount_value';

    /**
     * @var DiscountRepository
     */
    private $discountRepo;

    /**
     * DiscountManager constructor.
     * @param DiscountRepository $discountRepository
     */
    public function __construct(DiscountRepository $discountRepository)
    {
        $this->discountRepo = $discountRepository;
    }

    /**
     * @param int $id
     * @return Discounts
     */
    public function getDiscountDetailsById(int $id): ?Discounts
    {
        return $this->discountRepo->find($id);
    }

    /**
     * @param int $planId
     * @return Discounts
     */
    public function getPlanDiscount(int $planId): ?Discounts
    {
        $discount = $this->discountRepo->getPlanDiscount($planId);
        if (!$discount->count()) {
            return null;
        }

        return $discount->first();
    }

    /**
     * @param string $promocode
     * @param int $categoryid
     * @return Discounts
     */
    public function getPromoCodeDiscount(string $promocode, int $categoryid): ?Discounts
    {
        $discount = $this->discountRepo->CheckPromocode($promocode,$categoryid);
        if (!$discount->count()) {
            return null;
        }

        return $discount->first();
    }

    /**
     * @param Discounts $discount
     * @param float $price
     * @return float
     */
    public function getDiscountAmount(Discounts $discount, float $price): float
    {
        return ($discount->getDiscountType() === Discounts::DISCOUNT_TYPE_PERCENTAGE) ?
            (($price * $discount->getDiscount()) / 100) :
            $discount->getDiscount();
    }

    /**
     * @param Discounts $discount
     * @param float $price
     * @return float
     */
    public function getDiscountedPrice(Discounts $discount, float $price): float
    {
        $discountAmount = $this->getDiscountAmount($discount, $price);
        $finalPrice = $price - $discountAmount;
        return ($finalPrice >= 0) ? $finalPrice : 0;
    }
}
