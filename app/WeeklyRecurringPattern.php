<?php

namespace App;

use App\Interfaces\RecurringDateInterface;
use App\Traits\RecurringPatternTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Class WeeklyRecurringPattern
 * @package App
 */
class WeeklyRecurringPattern extends RecurringPattern implements RecurringDateInterface
{
    use RecurringPatternTrait;

    /**
     * @var string
     */
    protected $table = 'weekly_recurring_patterns';

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