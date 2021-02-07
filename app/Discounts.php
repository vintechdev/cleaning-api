<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Discounts extends Model
{
    const DISCOUNT_TYPE_PERCENTAGE = 'percentage';
    const DISCOUNT_TYPE_FLAT = 'flat';

    use uuids;
	use HasApiTokens, Notifiable;
    protected $table = 'discounts';
    use SoftDeletes;
    protected $fillable = ['id'];
    public $incrementing = false;

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function getDiscountType(): string
    {
        return $this->discount_type;
    }
}
