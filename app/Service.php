<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Emadadly\LaravelUuid\Uuids;
//use Laravel\Passport\HasApiTokens;
//use Illuminate\Notifications\Notifiable;

class Service extends Model
{
    const SERVICE_TYPE_ONCE_OFF = 'oneof';
    const SERVICE_TYPE_HOURLY = 'hourly';

   // use HasApiTokens, Notifiable;
    protected $table = 'services';
    use SoftDeletes;
    protected $fillable = ['id'];
    // public $incrementing = false;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getServiceCost(): int
    {
        return $this->service_cost;
    }

    

    /**
     * @param int $hours
     * @param int|null $cost
     * @return int
     */
    public function getTotalCost(int $hours, int $cost = null): int
    {
        $cost = $cost ?: $this->getServiceCost();
        if ($this->service_type === self::SERVICE_TYPE_ONCE_OFF) {
            return $cost;
        }

        return $cost * $hours;
    }

    /**
     * @return bool
     */
    public function isDefaultService(): bool
    {
        return $this->is_default_service;
    }
}
