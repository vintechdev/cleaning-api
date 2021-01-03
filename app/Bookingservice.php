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
            ->getTotalCost($this->getFinalNumberOfHours(), $this->getInitialServiceCost());
        return $this;
    }

    /**
     * @return int|null
     */
    public function getInitialServiceCost(): ?int
    {
        return $this->initial_service_cost;
    }

    public function getFinalTotal(): ?int
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
}
