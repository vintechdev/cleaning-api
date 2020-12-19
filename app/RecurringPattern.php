<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RecurringPattern
 * @package App
 */
class RecurringPattern extends Model
{
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'recurring_patterns';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function recurringpatternable()
    {
        return $this->morphTo();
    }

    /**
     * @return int
     */
    public function getSeparationCount(): int
    {
        return $this->separation_count;
    }

    /**
     * @param int $separationCount
     * @return $this
     */
    public function setSeparationCount(int $separationCount): RecurringPattern
    {
        $this->separation_count = $separationCount;
        return $this;
    }

    /**
     * @param int $planId
     * @return $this
     */
    public function setSeparationCountFromPlan(int $planId): RecurringPattern
    {
        switch ($planId) {
            case Plan::WEEKLY:
                return $this->setSeparationCount(1);
            case Plan::BIWEEKLY:
                return $this->setSeparationCount(2);
            case Plan::MONTHLY:
                return $this->setSeparationCount(1);
            default:
                return $this;
        }
    }

    /**
     * @return Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }

    /**
     * @return BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}