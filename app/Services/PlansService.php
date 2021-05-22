<?php

namespace App\Services;

use App\Plan;
use App\Servicecategory;
use Illuminate\Support\Facades\DB;

class PlansService
{
    /**
     * @param Servicecategory|null $servicecategory
     * @return mixed
     */
    public function getAllPlansDetails(Servicecategory $servicecategory = null)
    {
        $plans = Plan::leftJoin('discounts', function($join) {
            $join->on('discounts.plan_id', '=', 'plans.id');
            $join->whereNull('discounts.deleted_at');
        });

        if ($servicecategory) {
            $plans->wherein('plans.id', $this->getAllPlanIdsByServiceCategory($servicecategory));
        }

        return $plans
            ->orderby('sequence','asc')
            ->get(['plans.*','discounts.discount','discounts.discount_type'])
            ->toarray();
    }

    /**
     * @param $planId
     * @param Servicecategory $servicecategory
     * @return bool
     */
    public function isPlanValidForServiceCategory($planId, Servicecategory $servicecategory): bool {
        $plans = $this->getAllPlanIdsByServiceCategory($servicecategory);
        return in_array($planId, $plans);
    }

    /**
     * @param Servicecategory|null $servicecategory
     * @return array
     */
    public function getAllPlanIdsByServiceCategory(Servicecategory $servicecategory = null): array
    {
        if ($servicecategory && !$servicecategory->isRecurring()) {
            return [Plan::ONCEOFF];
        }

        return Plan::getAllPlanIds();
    }
}