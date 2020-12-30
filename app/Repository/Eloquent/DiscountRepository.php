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
    public function getPlanDiscount($id): array
    {
        return $this->getModelClass()::where(['plan_id' =>$id])->limit(1)->get()->toArray();
       
    }
    public function CheckPromocode($promocode,$categoryid){

        $res = $this->getModelClass()::where('promocode',$promocode)->where('category_id',$categoryid)->limit(1)->get()->toArray();
        return $res;
    }
}