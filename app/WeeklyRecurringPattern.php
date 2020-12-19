<?php

namespace App;

use App\Interfaces\RecurringDateInterface;
use App\Traits\RecurringPatternTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Class WeeklyRecurringPattern
 * @package App
 */
class WeeklyRecurringPattern extends Model implements RecurringDateInterface
{
    use RecurringPatternTrait;

    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'weekly_recurring_patterns';

    /**
     * @return MorphOne
     */
    public function recurringPattern()
    {
        return $this->morphOne(RecurringPattern::class, 'recurringpatternable');
    }

    /**
     * @param Carbon $date
     * @return Carbon
     */
    public function getNextRecurringDate(Carbon $date): Carbon
    {
        return $date->modify('+' . $this->getRecurringPattern()->getSeparationCount() . ' weeks');
    }

    /**
     * @param Carbon $date
     * @return Carbon
     */
    public function getNextValidDateRelativeTo(Carbon $date): Carbon
    {
        $startDateTime = $this->getRecurringPattern()->getEvent()->getStartDateTime();
        if ($startDateTime->greaterThan($date)) {
            return $startDateTime;
        }

        $totalDays = $date->diffInDays($startDateTime);
        $weeks = $totalDays/7;
        $separationCount = $this->getRecurringPattern()->getSeparationCount();
        if ($weeks < $separationCount) {
            return $this->getNextRecurringDate($startDateTime);
        }

        $weeksToAdd = floor($weeks) - (floor($weeks) % $separationCount);
        $startDateTime->modify('+' . $weeksToAdd . ' weeks');

        return $this->getNextRecurringDate($startDateTime);
    }

    /**
     * @return string
     */
    protected function getDateModifier(): string
    {
        return 'week';
    }
}