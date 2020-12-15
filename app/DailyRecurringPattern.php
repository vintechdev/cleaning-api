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
    public function getNextValidDateRelativeTo(Carbon $date): Carbon
    {
        $startDateTime = $this->getRecurringPattern()->getEvent()->getStartDateTime();
        if ($startDateTime->greaterThan($date)) {
            return $startDateTime;
        }

        $days = $date->diff($startDateTime)->d;
        $separationCount = $this->getRecurringPattern()->getSeparationCount();
        if ($days < $separationCount) {
            return $this->getNextRecurringDate($startDateTime);
        }

        $daysToAdd = floor($days);
        $startDateTime->modify('+' . $daysToAdd . ' days');

        return floor($days) !== $days ?
            $this->getNextRecurringDate($startDateTime) :
            $startDateTime;
    }

    /**
     * @return string
     */
    protected function getDateModifier(): string
    {
        return 'days';
    }
}