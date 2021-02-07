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
     * @param int $planId
     * @return array
     */
    public function getPlanDiscountDetails(int $planId): array
    {
        $discount = $this->discountRepo->getPlanDiscount($planId);
        if (!$discount->count()) {
            return [];
        }

        return $this->buildDiscountDetails($discount->first());
    }

    /**
     * @param string $promocode
     * @param int $categoryid
     * @return array
     */
    public function getPromoCodeDetails(string $promocode, int $categoryid): array
    {
        $discount = $this->discountRepo->CheckPromocode($promocode,$categoryid);
        if (!$discount->count()) {
            return [];
        }

        return $this->buildDiscountDetails($discount->first());
    }

    /**
     * @param array $discountDetails
     * @param float $price
     * @return float
     */
    public function getDiscountAmount(array $discountDetails, float $price): float
    {
        if (!$discountDetails) {
            return 0;
        }

        return ($discountDetails[self::DISCOUNT_TYPE] === Discounts::DISCOUNT_TYPE_PERCENTAGE) ?
            (($price * $discountDetails[self::DISCOUNT_VALUE]) / 100) :
            $discountDetails[self::DISCOUNT_VALUE];
    }

    /**
     * @param array $discountDetails
     * @param float $price
     * @return float
     */
    public function getDiscountedPrice(array $discountDetails, float $price): float
    {
        $discountAmount = $this->getDiscountAmount($discountDetails, $price);
        $finalPrice = $price - $discountAmount;
        return ($finalPrice >= 0) ? $finalPrice : 0;
    }

    private function buildDiscountDetails(Discounts $discount): array
    {
        return [
            self::DISCOUNT_TYPE => $discount->getDiscountType(),
            self::DISCOUNT_VALUE => $discount->getDiscount()
        ];
    }
}
