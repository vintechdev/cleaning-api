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
        $this->service = $service;
        return $this;
    }
    
    /**
     * @param int $hours
     * @return $this
     */
    public function setFinalNumberOfHours(int $hours): Bookingservice
    {
        $this->final_number_of_hours = $hours;
        return $this->updateFinalTotal();
    }

    /**
     * @return int
     */
    public function getFinalNumberOfHours(): ?int
    {
        return $this->final_number_of_hours;
    }

    /**
     * @return $this
     */
    public function updateFinalTotal(): Bookingservice
    {
        if (is_null($this->getFinalNumberOfHours())) {
            return $this;
        }
        $this->final_service_cost = $this
            ->getService()
            ->getTotalCost($this->getFinalNumberOfHours());
        return $this;
    }

    /**
     * @param int $intitialNumberOfHours
     * @return $this
     */
    public function setInitialNumberOfHours(int $intitialNumberOfHours)
    {
        $this->initial_number_of_hours = $intitialNumberOfHours;
        $this->updateInitialTotal();
        return $this;
    }

    /**
     * @return int|null
     */
    public function getInitialNumberOfHours(): ?int
    {
        return $this->initial_number_of_hours;
    }

    /**
     * @return $this
     */
    public function updateInitialTotal()
    {
        if (is_null($this->getInitialNumberOfHours())) {
            return $this;
        }

        $this->initial_service_cost = $this
            ->getService()
            ->getTotalCost($this->getInitialNumberOfHours());
        return $this;
    }

    /**
     * @param int $intialServiceCost
     * @return $this
     */
    public function setInitialServiceCost(int $intialServiceCost)
    {
        $this->initial_service_cost = $intialServiceCost;
        return $this;
    }

    /**
     * @param int $finalServiceCost
     * @return $this
     */
    public function setFinalServiceCost(int $finalServiceCost)
    {
        $this->final_service_cost = $finalServiceCost;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getInitialServiceCost(): ?int
    {
        return $this->initial_service_cost;
    }

    public function getFinalServiceCost(): ?int
    {
        return $this->final_service_cost;
    }

    /**
     * @return bool
     */
    public function isRemoved(): bool
    {
        return $this->is_removed;
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
        return $this->is_initiallly_added;
    }

    /**
     * @param bool $isInitiallyAdded
     * @return $this
     */
    public function setInitiallyAdded(bool $isInitiallyAdded)
    {
        $this->is_initiallly_added = $isInitiallyAdded;
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
}
