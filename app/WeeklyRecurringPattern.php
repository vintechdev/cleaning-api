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
    public function getNextValidDateRelativeTo(Carbon $date): ?Carbon
    {
        $endDateTime = $this->getRecurringPattern()->getEvent()->getEndDateTime();
        if ($endDateTime && $date->greaterThanOrEqualTo($endDateTime)) {
            return null;
        }

        $startDateTime = $this->getRecurringPattern()->getEvent()->getStartDateTime();
        if ($startDateTime->greaterThan($date)) {
            return $startDateTime;
        }

        $totalDays = $date->floatDiffInDays($startDateTime);
        $weeks = $totalDays/7;
        $separationCount = $this->getRecurringPattern()->getSeparationCount();
        if ($weeks < $separationCount) {
            $nextDate = $this->getNextRecurringDate($startDateTime);
            if ($endDateTime && $nextDate->greaterThanOrEqualTo($endDateTime)) {
                return null;
            }
            return $nextDate;
        }

        $weeksToAdd = floor($weeks) - (floor($weeks) % $separationCount);
        $startDateTime->modify('+' . $weeksToAdd . ' weeks');

        $nextDate = $this->getNextRecurringDate($startDateTime);
        if ($endDateTime && $nextDate->greaterThanOrEqualTo($endDateTime)) {
            return null;
        }
        return $nextDate;
    }

    /**
     * @return string
     */
    protected function getDateModifier(): string
    {
        return 'week';
    }
}