<?php


namespace App\Interfaces;

use App\RecurringPattern;
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

    /**
     * @return RecurringPattern
     */
    public function getRecurringPattern(): RecurringPattern;

    /**
     * @param Carbon $date
     * @return Carbon
     */
    public function getNextValidDateRelativeTo(Carbon $date): ?Carbon;

    /**
     * Returns the date on the offset passed
     * If the dates are 10-10-2020, 12-10-2020 and 14-10-2020 and the offset is 2 it will
     * return 12-10-2020
     * @param int $offset
     * @param Carbon $relativeDate
     * @return Carbon
     */
    public function getDateByOffset(int $offset, Carbon $relativeDate = null): Carbon;

    /**
     * Checks if the date passed is a belongs to the series of recurring dates
     * @param Carbon $date
     * @return bool
     */
    public function isValidRecurringDate(Carbon $date): bool;
}