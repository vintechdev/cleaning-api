<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
	use SoftDeletes;

	const BIWEEKLY = 1;
	const ONCEOFF = 2;
	const WEEKLY = 3;
	const MONTHLY = 4;

    protected $table = 'plans';
    protected $fillable = ['id'];
    public $incrementing = false;

    /**
     * @param $planId
     * @return bool
     */
    public static function isValidPlan($planId) {
        if (
            !in_array(
                $planId,
                static::getAllPlanIds()
            )
        ) {
            return false;
        }

        return true;
    }

    /**
     * @return int[]
     */
    public static function getAllPlanIds()
    {
        return [
            self::BIWEEKLY,
            self::ONCEOFF,
            self::WEEKLY,
            self::MONTHLY
        ];
    }
}
