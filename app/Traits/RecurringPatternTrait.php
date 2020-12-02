<?php

namespace App\Traits;

use App\RecurringPattern;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Trait RecurringPatternTrait
 * @package App\Traits
 */
trait RecurringPatternTrait
{
    private $separationCount;

    abstract public function recurringPattern(): MorphOne;

    /**
     * @param int $separationCount
     * @return $this
     */
    public function setSeparationCount(int $separationCount): RecurringPattern
    {
        $this->separationCount = $separationCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getSeparationCount(): int
    {
        return $this->separationCount;
    }
}