<?php

namespace App\Factory\Booking;

use App\Event;
use App\Exceptions\Booking\Factory\RecurringPatternFactoryException;
use App\MonthlyRecurringPattern;
use App\Plan;
use App\RecurringPattern;
use App\WeeklyRecurringPattern;
use Carbon\Carbon;

/**
 * Class RecurringPatternFactory
 * @package App\Factory\Booking
 */
class RecurringPatternFactory
{
    /**
     * @param int $planId
     * @param Event $event
     * @return RecurringPattern
     * @throws RecurringPatternFactoryException
     */
    public function create(int $planId, Event $event): RecurringPattern
    {
        /** @var Carbon $startDate */
        $startDate = $event->startDate;

        switch ($planId) {
            case Plan::WEEKLY:
                $recurringPattern = new WeeklyRecurringPattern();
                $recurringPattern->dayOfWeek = $startDate->dayOfWeek;
                $recurringPattern->separationCount = 1;
                break;
            case Plan::BIWEEKLY:
                $recurringPattern = new WeeklyRecurringPattern();
                $recurringPattern->dayOfWeek = $startDate->dayOfWeek;
                $recurringPattern->separationCount = 2;
                break;
            case Plan::MONTHLY:
                $recurringPattern = new MonthlyRecurringPattern();
                $recurringPattern->dayOfMonth = $startDate->format('d');
                $recurringPattern->separationCount = 1;
                break;
            default:
                throw new RecurringPatternFactoryException('Recurring pattern does not exist for the plan');
        }

        return $recurringPattern;
    }
}