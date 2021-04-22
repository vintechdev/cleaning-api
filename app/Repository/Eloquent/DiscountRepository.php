<?php

namespace App\Repository\Eloquent;
use Illuminate\Database\Eloquent\Collection;
use App\Discounts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;


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

    /**
     * @param array $filters
     * @return Collection
     */
    public function getAll($filters = []): Collection
    {
        $query = $this->getModel()::query()->select(["discounts.*", 
           \DB::raw("IF(discounts.plan_id IS NOT NULL, (SELECT plan_name from plans WHERE plans.id = discounts.plan_id),'') as plan_name"),
            \DB::raw("IF(discounts.category_id IS NOT NULL, (SELECT name from service_categories WHERE service_categories.id = discounts.category_id),'') as category_name") 
        ]);


        if (Arr::get($filters,'category_id')) {
            $query->where('category_id', '=',  Arr::get($filters,'category_id'));
        }

        if (Arr::get($filters,'plan_id')) {
            $query->where('plan_id', '=',  Arr::get($filters,'plan_id'));
        }

        if (Arr::get($filters,'discount_type')) {
            $query->where('discount_type', 'like',  Arr::get($filters,'discount_type'));
        }

        if (Arr::get($filters,'percentage')) {
            $query->where('percentage', '=',  Arr::get($filters,'percentage'));
        }

        if (Arr::get($filters,'promocode')) {
            $query->where('promocode', 'like',  Arr::get($filters,'promocode'));
        }

        if (!Arr::get($filters, 'trashed')) {
            $query->whereNull('deleted_at');
        }

        return $query->orderBy('created_at', 'DESC')->get();
    }

    public function edit(int $id)
    {
        return $this->find($id);
    }

    /**
     * @param int $id
     * @param $data
     * @return Discounts
     */
    public function update(int $id, $data): Discounts
    {
        $entity = $this->find($id);

        return $this->save($entity, $data);
    }

    /**
     * @param Discounts $entity
     * @param $data
     * @return Discounts
     */
    public function save(Discounts $entity, $data): Discounts
    {
        $entity->discount_type = Arr::get($data, 'discount_type');
        $entity->discount = Arr::get($data, 'discount');
        $entity->promocode = Arr::get($data, 'promocode');

        if (Arr::get($data, 'category_id')) {
            $entity->category_id = Arr::get($data, 'category_id');
        }

        if (Arr::get($data, 'plan_id')) {
            $entity->plan_id = Arr::get($data, 'plan_id');
        }

        $entity->save();

        return $entity;
    }


    /**
     * @param $id
     * @return bool
     */
    public function delete($id): bool
    {
        try {
            return $this->find($id)->delete();
        } catch (\Exception $e) {
            return  false;
        }
    }
}