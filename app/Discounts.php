<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Discounts extends Model
{
    const DISCOUNT_TYPE_PERCENTAGE = 'percentage';
    const DISCOUNT_TYPE_FLAT = 'flat';

	use HasApiTokens, Notifiable;
    protected $table = 'discounts';
    use SoftDeletes;
    protected $guarded = ['id'];
    public $incrementing = false;

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function getDiscountType(): string
    {
        return $this->discount_type;
    }

    public function isPlanDiscount(): bool
    {
        return !is_null($this->plan_id);
    }

    public function isPromoDiscount(): bool
    {
        return !is_null($this->promocode);
    }

    /**
     * @return string[]
     */
    public static function getTypes(): array
    {
        return [
            self::DISCOUNT_TYPE_PERCENTAGE,
            self::DISCOUNT_TYPE_FLAT
        ];
    }
}
