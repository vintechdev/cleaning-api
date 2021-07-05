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
    public function getServiceCost(): float
    {
        return $this->service_cost;
    }

    public function getName() {
        return $this->name;
    }

    /**
     * @param int $hours
     * @param int|null $cost
     * @return int
     */
    public function getTotalCost(float $hours = null, float $cost = null): float
    {
        $cost = ($cost && $this->getAllowPriceOverride()) ? $cost : $this->getServiceCost();
        if ($this->getServiceType() === self::SERVICE_TYPE_ONCE_OFF) {
            return $cost;
        }

        return $cost * ($hours ?: 0);
    }

    /**
     * @param float $totalServiceCost
     * @param float $numberOfHours
     * @return float|null
     */
    public function getBaseCost(float $totalServiceCost, float $numberOfHours): ?float
    {
        if ($this->getServiceType() === self::SERVICE_TYPE_HOURLY) {
            if (!$numberOfHours) {
                return null;
            }

            return $totalServiceCost/$numberOfHours;
        }

        return  $totalServiceCost;
    }

    /**
     * @return string
     */
    public function getServiceType(): string
    {
        return $this->service_type;
    }

    /**
     * @return bool
     */
    public function isDefaultService(): bool
    {
        return (bool) $this->is_default_service;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function servicecategory()
    {
        return $this->belongsTo(Servicecategory::class, 'category_id', 'id');
    }

    /**
     * @return Service|null
     */
    public function getServicecategory(): ?Servicecategory
    {
        return $this->servicecategory;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    public function getAllowPriceOverride(): bool
    {
        return (bool) $this->allow_price_override;
    }

    /**
     * @return int|null
     */
    public function getMaxHours(): ?int
    {
        return $this->max_hours;
    }

    /**
     * @return int|null
     */
    public function getMinHours(): ?int
    {
        return $this->min_hours;
    }
}
