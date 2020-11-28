<?php


namespace App\Interfaces;

use Carbon\Carbon;

/**
 * Interface RecurringDateInterface
 * @package App\Interfaces
 */
interface RecurringDateInterface
{
    /**
     * @param Carbon $date
     * @return Carbon
     */
    public function getNextRecurringDate(Carbon $date): Carbon;
}