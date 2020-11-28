<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;

class Plan extends Model
{
	use uuids, SoftDeletes;

	const BIWEEKLY = 1;
	const ONCEOFF = 2;
	const WEEKLY = 3;
	const MONTHLY = 4;

    protected $table = 'plans';
    protected $fillable = ['id'];
    public $incrementing = false;

    public static function isValidPlan($planId) {
        if (
            !in_array(
                $planId,
                [
                    self::BIWEEKLY,
                    self::ONCEOFF,
                    self::WEEKLY,
                    self::MONTHLY
                ]
            )
        ) {
            return false;
        }

        return true;
    }
}
