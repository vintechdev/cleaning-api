<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use PhpParser\Node\Expr\Cast\String_;

class Bookingservice extends Model
{
    use uuids;
	use HasApiTokens, Notifiable;
    protected $table = 'booking_services';
    use SoftDeletes;
    protected $fillable = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * @return Service|null
     */
    public function getService(): ?Service
    {
        return $this->service;
    }

    /**
     * @param Service $service
     * @return $this
     */
    public function setService(Service $service)
    {
        $this->service()->associate($service);
        return $this;
    }
    
    /**
     * @param int $hours
     * @return $this
     */
    public function setFinalNumberOfHours(float $hours): Bookingservice
    {
        $this->final_number_of_hours = $hours;
        return $this->updateFinalTotal();
    }

    /**
     * @return int
     */
    public function getFinalNumberOfHours(): ?float
    {
        return $this->final_number_of_hours;
    }

    /**
     * @return $this
     */
    public function updateFinalTotal(): Bookingservice
    {
        $this->final_service_cost = $this
            ->getService()
            ->getTotalCost($this->getFinalNumberOfHours());
        return $this;
    }

    /**
     * @param int $intitialNumberOfHours
     * @return $this
     */
    public function setInitialNumberOfHours(float $intitialNumberOfHours)
    {
        $this->initial_number_of_hours = $intitialNumberOfHours;
        $this->updateInitialTotal();
        return $this;
    }

    /**
     * @return int|null
     */
    public function getInitialNumberOfHours(): ?float
    {
        return $this->initial_number_of_hours;
    }

    /**
     * @return $this
     */
    public function updateInitialTotal()
    {
        $this->initial_service_cost = $this
            ->getService()
            ->getTotalCost($this->getInitialNumberOfHours());
        return $this;
    }

    /**
     * @param int $intialServiceCost
     * @return $this
     */
    public function setInitialServiceCost(float $intialServiceCost)
    {
        $this->initial_service_cost = $intialServiceCost;
        return $this;
    }

    /**
     * @param int $finalServiceCost
     * @return $this
     */
    public function setFinalServiceCost(float $finalServiceCost)
    {
        $this->final_service_cost = $finalServiceCost;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getInitialServiceCost(): ?float
    {
        return $this->initial_service_cost;
    }

    public function getFinalServiceCost(): ?float
    {
        return $this->final_service_cost;
    }

    /**
     * @return bool
     */
    public function isRemoved(): bool
    {
        return $this->is_removed != 0;
    }

    /**
     * @param bool $isRemoved
     * @return $this
     */
    public function setRemoved(bool $isRemoved): Bookingservice
    {
        $this->is_removed = $isRemoved;
        return $this;
    }

    /**
     * @return bool
     */
    public function isInitiallyAdded(): bool {
        return $this->is_initially_added != 0;
    }

    /**
     * @param bool $isInitiallyAdded
     * @return $this
     */
    public function setInitiallyAdded(bool $isInitiallyAdded)
    {
        $this->is_initially_added = $isInitiallyAdded;
        return $this;
    }

    /**
     * @param int $bookingId
     * @return $this
     */
    public function setBookingId(int $bookingId)
    {
        $this->booking_id = $bookingId;
        return $this;
    }

    /**
     * @return int
     */
    public function getBookingId(): int
    {
        return $this->booking_id;
    }

    /**
     * @return float|null
     */
    public function getBaseInitialServiceCost(): ?float
    {
        if (!$this->getInitialServiceCost()) {
            return null;
        }

        return $this
            ->getService()
            ->getBaseCost($this->getInitialServiceCost(), $this->getInitialNumberOfHours());
    }

    /**
     * @return bool
     */
    public function hasChanged(): bool
    {
        if (!$this->isInitiallyAdded() || $this->isRemoved()) {
            return false;
        }

        if (
            !is_null($this->getFinalServiceCost()) &&
            $this->getService()->getServiceType() === Service::SERVICE_TYPE_HOURLY &&
            $this->getInitialNumberOfHours() != $this->getFinalNumberOfHours()
        ) {
            return true;
        }

        return false;
    }
}
