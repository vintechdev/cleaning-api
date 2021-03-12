<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Setting extends Model
{
    use HasApiTokens, Notifiable;
    use Uuids;
    protected $table = 'settings';
    use SoftDeletes;
    protected $fillable = ['id'];

    /**
     * @return float
     */
    public static function getStripeServiceFeePercentage(): float
    {
        $setting = self::where(['type' => 'payment', 'key' => 'stripe_service_fee_percentage'])->first();
        return $setting->value;
    }
}
