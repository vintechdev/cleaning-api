<?php

namespace App\Repository\Eloquent;

use App\Event;
use App\RecurringBooking;
use Carbon\Carbon;

/**
 * Class RecurringBookingRepository
 * @package App\Repository\Eloquent
 */
class RecurringBookingRepository extends AbstractBaseRepository
{
    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return RecurringBooking::class;
    }

    /**
     * @param Event $event
     * @param Carbon $date
     * @return RecurringBooking|null
     */
    public function findByEventAndDate(Event $event, Carbon $date): ?RecurringBooking
    {
        return RecurringBooking::findByEventIdAndRecurredDate($event->getId(), $date);
    }
}
