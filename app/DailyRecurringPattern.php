<?php

namespace App;

use App\Interfaces\RecurringDateInterface;
use Carbon\Carbon;

/**
 * Class DailyRecurringPattern
 * @package App
 */
class DailyRecurringPattern extends RecurringPattern implements RecurringDateInterface
{
    /**
     * @var string
     */
    protected $table = 'daily_recurring_patterns';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function recurringPattern()
    {
        return $this->morphOne(RecurringPattern::class, 'recurringPatternable');
    }

    public function getNextRecurringDate(Carbon $date): Carbon
    {
        // TODO: Implement getNextRecurringDate() method.
    }
}