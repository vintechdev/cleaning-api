<?php

namespace App\Factory\Booking;

use App\Event;
use App\Exceptions\Booking\Factory\RecurringPatternFactoryException;
use App\Interfaces\RecurringDateInterface;
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
     * @return RecurringDateInterface
     * @throws RecurringPatternFactoryException
     */
    public function create(int $planId, Event $event): RecurringDateInterface
    {
        /** @var Carbon $startDate */
        $startDate = $event->start_date;

        switch ($planId) {
            case Plan::WEEKLY:
                $recurringPattern = new WeeklyRecurringPattern();
                $recurringPattern->day_of_week = $startDate->dayOfWeek;
                break;
            case Plan::BIWEEKLY:
                $recurringPattern = new WeeklyRecurringPattern();
                $recurringPattern->day_of_week = $startDate->dayOfWeek;
                break;
            case Plan::MONTHLY:
                $recurringPattern = new MonthlyRecurringPattern();
                $recurringPattern->day_of_month = $startDate->format('d');
                break;
            default:
                throw new RecurringPatternFactoryException('Recurring pattern does not exist for the plan');
        }

        return $recurringPattern;
    }
}