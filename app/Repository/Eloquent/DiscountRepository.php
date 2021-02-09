<?php

namespace App\Repository\Eloquent;
use Illuminate\Database\Eloquent\Collection;
use App\Discounts;


/**
 * Class DiscountRepository
 * @package App\Repository\Eloquent
 */
class DiscountRepository extends AbstractBaseRepository
{
    protected function getModelClass(): string
    {
        return Discounts::class;
    }
    public function getPlanDiscount($id): Collection
    {
        return $this->getModelClass()::where(['plan_id' =>$id])->where('deleted_at', null)->limit(1)->get();
       
    }
    public function CheckPromocode($promocode,$categoryid): Collection {

        $res = $this->getModelClass()::where('promocode',$promocode)->where('category_id',$categoryid)->where('deleted_at', null)->limit(1)->get();
        return $res;
    }
}