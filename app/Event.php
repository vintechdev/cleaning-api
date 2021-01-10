<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Event
 * @package App
 */
class Event extends Model
{
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'events';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Carbon
     */
    public function getStartDateTime(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->start_date);
    }

    /**
     * @param Carbon $date
     * @return $this
     */
    public function setEndDateTime(Carbon $date)
    {
        $this->end_date = $date->format('Y-m-d H:i:s');
        return $this;
    }

    /**
     * @return Carbon|null
     */
    public function getEndDateTime(): ?Carbon
    {
        if ($this->end_date) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $this->end_date);
        }

        return null;
    }

    /**
     * @return HasOne
     */
    public function recurringPattern()
    {
        $this->hasOne(RecurringPattern::class, 'event_id', 'id');
    }
}