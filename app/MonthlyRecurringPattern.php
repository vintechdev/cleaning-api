<?php

namespace App;

use App\Interfaces\RecurringDateInterface;
use App\Traits\RecurringPatternTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Class MonthlyRecurringPattern
 * @package App
 */
class MonthlyRecurringPattern extends Model implements RecurringDateInterface
{
    use RecurringPatternTrait;

    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'monthly_recurring_patterns';

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
        return $date->modify('+' . $this->getRecurringPattern()->getSeparationCount() . ' months');
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

        $months = $date->floatdiffInMonths($startDateTime);
        $separationCount = $this->getRecurringPattern()->getSeparationCount();
        if ($months < $separationCount) {
            $nextDate = $this->getNextRecurringDate($startDateTime);
            if ($endDateTime && $nextDate->greaterThanOrEqualTo($endDateTime)) {
                return null;
            }
            return $nextDate;
        }

        $monthsToAdd = floor($months) - (floor($months) % $separationCount);
        $startDateTime->modify('+' . $monthsToAdd . ' months');

        $nextDate = $this->getNextRecurringDate($startDateTime);
        if ($endDateTime && $nextDate->greaterThanOrEqualTo($endDateTime)) {
            return null;
        }
        return $nextDate;
    }

    /**
     * @param Carbon $date
     * @return bool
     */
    public function isValidRecurringDate(Carbon $date): bool
    {
        /** @var Carbon $nextValidDate */
        $nextValidDate = $this->getNextValidDateRelativeTo($date);
        return $nextValidDate->floatDiffInMonths($date) == $this->getRecurringPattern()->getSeparationCount();
    }

    /**
     * @return string
     */
    protected function getDateModifier(): string
    {
        return 'months';
    }
}