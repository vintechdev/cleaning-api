<?php

namespace App;

use App\Interfaces\RecurringDateInterface;
use Carbon\Carbon;

/**
 * Class WeeklyRecurringPattern
 * @package App
 */
class WeeklyRecurringPattern extends RecurringPattern implements RecurringDateInterface
{
    /**
     * @var string
     */
    protected $table = 'weekly_recurring_patterns';

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