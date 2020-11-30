<?php

namespace App;

use App\Interfaces\RecurringDateInterface;
use App\Traits\RecurringPatternTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Class DailyRecurringPattern
 * @package App
 */
class DailyRecurringPattern extends RecurringPattern implements RecurringDateInterface
{
    use RecurringPatternTrait;

    /**
     * @var string
     */
    protected $table = 'daily_recurring_patterns';

    /**
     * @return MorphOne
     */
    public function recurringPattern(): MorphOne
    {
        return $this->morphOne(RecurringPattern::class, 'recurringPatternable');
    }

    public function getNextRecurringDate(Carbon $date): Carbon
    {
        // TODO: Implement getNextRecurringDate() method.
    }
}