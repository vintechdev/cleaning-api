<?php

namespace App\Repository\Eloquent;

use App\Booking;
use App\Event;
use App\RecurringBooking;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

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

    /**
     * @param Event $event
     * @param array $dates
     * @return Collection
     */
    public function findAllByEventAndDates(Event $event, array $dates): Collection
    {
        $modifiedDates = array_map(function (Carbon $date) {
            return $date->format('Y-m-d H:i:s');
        }, $dates);

        return RecurringBooking::where(['event_id' => $event->getId()])->whereIn('recurred_timestamp', $modifiedDates)->get();
    }

    /**
     * @param Event $event
     * @return Collection
     */
    public function findAllByEvent(Event $event): Collection
    {
        return RecurringBooking::where(['event_id' => $event->getId()])->get();
    }

    /**
     * @param Event $event
     * @return Collection
     */
    public function findAllRescheduledByEvent(Event $event): Collection
    {
        return RecurringBooking::where(['event_id' => $event->getId()])->where(['is_rescheduled'=> 1])->get();
    }

    /**
     * @param Booking $booking
     * @return RecurringBooking|null
     */
    public function findByChildBooking(Booking $booking): ?RecurringBooking
    {
        if (!$booking->isChildBooking()) {
            return null;
        }

        $recurringBooking = RecurringBooking::where(['booking_id' => $booking->getId()]);
        if (!$recurringBooking->count()) {
            return null;
        }

        return $recurringBooking->first();
    }
}
