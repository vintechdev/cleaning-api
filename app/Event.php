<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

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
     * @return HasOne
     */
    public function recurringPattern()
    {
        $this->hasOne(RecurringPattern::class, 'event_id', 'id');
    }
}