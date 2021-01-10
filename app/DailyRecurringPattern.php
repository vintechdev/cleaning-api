<?php

namespace App;

use App\Interfaces\RecurringDateInterface;
use App\Traits\RecurringPatternTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Class DailyRecurringPattern
 * @package App
 */
class DailyRecurringPattern extends Model implements RecurringDateInterface
{
    use RecurringPatternTrait;

    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'daily_recurring_patterns';

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
        return $date->modify('+' . $this->getRecurringPattern()->getSeparationCount() . ' days');
    }

    /**
     * @param Carbon $date
     * @return Carbon
     */
    public function getNextValidDateRelativeTo(Carbon $date): ?Carbon
    {
        $endDateTime = $this->getRecurringPattern()->getEvent()->getEndDateTime();
        if ($date->greaterThan($endDateTime)) {
            return null;
        }
        $startDateTime = $this->getRecurringPattern()->getEvent()->getStartDateTime();
        if ($startDateTime->greaterThan($date)) {
            return $startDateTime;
        }

        $days = $date->floatDiffInDays($startDateTime);
        $separationCount = $this->getRecurringPattern()->getSeparationCount();
        if ($days < $separationCount) {
            $nextDate = $this->getNextRecurringDate($startDateTime);
            if ($nextDate->greaterThan($endDateTime)) {
                return null;
            }
            return $nextDate;
        }

        $daysToAdd = floor($days);
        $startDateTime->modify('+' . $daysToAdd . ' days');

        if (floor($days) !== $days) {
            $nextDate = $this->getNextRecurringDate($startDateTime);
            if ($nextDate->greaterThan($endDateTime)) {
                return null;
            }
            return $nextDate;
        }

        return $startDateTime;
    }

    /**
     * @param Carbon $date
     * @return bool
     */
    public function isValidRecurringDate(Carbon $date): bool
    {
        /** @var Carbon $nextValidDate */
        $nextValidDate = $this->getNextValidDateRelativeTo($date);
        return $nextValidDate->floatDiffInDays($date) == $this->getRecurringPattern()->getSeparationCount();
    }

    /**
     * @return string
     */
    protected function getDateModifier(): string
    {
        return 'days';
    }
}