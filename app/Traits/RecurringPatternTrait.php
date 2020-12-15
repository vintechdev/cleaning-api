<?php

namespace App\Traits;

use App\RecurringPattern;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Trait RecurringPatternTrait
 * @package App\Traits
 */
trait RecurringPatternTrait
{
    private $separationCount;

    /**
     * @return MorphOne
     */
    abstract public function recurringPattern(): MorphOne;

    /**
     * @return string
     */
    abstract protected function getDateModifier(): string;

    /**
     * @return RecurringPattern
     */
    public function getRecurringPattern(): RecurringPattern
    {
        return $this->recurringPattern;
    }

    /**
     * @param int $separationCount
     * @return $this
     */
    public function setSeparationCount(int $separationCount = 1)
    {
        $this->separation_count = $separationCount;
        return $this;
    }

    /**
     * Returns the date on the offset passed
     * If the dates are 10-10-2020, 12-10-2020 and 14-10-2020 and the offset is 2 it will
     * return 12-10-2020
     * @param $offset
     * @return Carbon
     */
    public function getDateByOffset(int $offset): Carbon
    {
        $separation = $this->getRecurringPattern()->getSeparationCount() * ($offset - 1);
        $startDate = $this->getRecurringPattern()->getEvent()->getStartDateTime();
        return  $startDate->modify('+' . $separation . ' ' . $this->getDateModifier());
    }
}