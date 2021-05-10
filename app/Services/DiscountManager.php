<?php

namespace App\Services;

use App\Discounts;
use App\Repository\Eloquent\DiscountRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

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
     * @param Discounts[] $discounts
     * @param float $price
     * @return float
     */
    public function getDiscountedPrice(array $discounts, float $price): float
    {
        $discountAmount = 0;
        foreach ($discounts as $discount) {
            $discountAmount += $this->getDiscountAmount($discount, $price);
        }

        $finalPrice = $price - $discountAmount;
        return ($finalPrice >= 0) ? $finalPrice : 0;
    }

    public function getAll(array $filters = []) : Collection
    {
        return $this->discountRepo->getAll($filters);
    }

    public function update(int $id, array $data): Discounts
    {
        $data = $this->validate($data);
        return $this->discountRepo->update($id, $data);
    }

    public function create(array $data): Discounts
    {
        $data = $this->validate($data);

        /** @var Discounts $discount */
        $discount = $this->discountRepo->create($data, true);

        return  $discount;
    }

    private function validate(array $data)
    {
        $validator = Validator::make($data, [
            'discount_category' => 'required|in:plan,promo',
            'discount_type' => 'required|in:' . implode(',', $this->getDiscountTypes()) . '|',
            'category_id' => 'exclude_if:discount_category,plan|required|integer',
            'plan_id' => 'exclude_if:discount_category,promo|required|integer',
            'discount' => 'required|numeric' . ($data['discount_type'] === 'percentage' ? '|lte:100' : ''),
            'promocode' => 'exclude_if:discount_category,plan|required|string'
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->messages());
        }

        if ($data['discount_category'] === 'plan') {
            unset($data['promocode']);
            unset($data['category_id']);
        } else {
            unset($data['plan_id']);
        }

        unset($data['discount_category']);

        return $data;
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id): bool
    {
        return $this->discountRepo->delete($id);
    }

    /**
     * @return string[]
     */
    public function getDiscountTypes(): array
    {
        return Discounts::getTypes();
    }
}
