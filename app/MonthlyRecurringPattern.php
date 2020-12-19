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
    public function getNextValidDateRelativeTo(Carbon $date): Carbon
    {
        $startDateTime = $this->getRecurringPattern()->getEvent()->getStartDateTime();
        if ($startDateTime->greaterThan($date)) {
            return $startDateTime;
        }

        $months = $date->diffInMonths($startDateTime);
        $separationCount = $this->getRecurringPattern()->getSeparationCount();
        if ($months < $separationCount) {
            return $this->getNextRecurringDate($startDateTime);
        }

        $monthsToAdd = floor($months) - (floor($months) % $separationCount);
        $startDateTime->modify('+' . $monthsToAdd . ' months');

        return $this->getNextRecurringDate($startDateTime);
    }

    /**
     * @return string
     */
    protected function getDateModifier(): string
    {
        return 'months';
    }
}