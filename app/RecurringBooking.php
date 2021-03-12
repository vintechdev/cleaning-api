<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RecurringBooking
 * @package App
 */
class RecurringBooking extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * @return Booking
     */
    public function getBooking(): Booking
    {
        return $this->booking;
    }

    /**
     * @return int
     */
    public function getEventId(): int
    {
        return $this->event_id;
    }

    /**
     * @param int $eventId
     * @return $this
     */
    public function setEventId(int $eventId): RecurringBooking
    {
        $this->event_id = $eventId;
        return $this;
    }

    /**
     * @param Carbon $date
     * @return $this
     */
    public function setRecurredDate(Carbon $date): RecurringBooking
    {
        $this->recurred_date = $date->format('Y-m-d H:i:s');
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getRecurredDate(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->recurred_timestamp);
    }

    /**
     * @param int $eventId
     * @param Carbon $date
     * @return RecurringBooking|null
     */
    public static function findByEventIdAndRecurredDate(int $eventId, Carbon $date): ?RecurringBooking
    {
        $recurringBooking = self::where([
            'event_id' => $eventId,
            'recurred_timestamp' => $date->format('Y-m-d H:i:s')
        ]);

        if ($recurringBooking->count()) {
            return $recurringBooking->first();
        }
        return null;
    }

    /**
     * @return bool
     */
    public function isRescheduled(): bool
    {
        return (bool)$this->is_rescheduled;
    }

    /**
     * @param bool $isRescheduled
     * @return $this
     */
    public function setRescheduled(bool $isRescheduled = true)
    {
        $this->is_rescheduled = $isRescheduled ? 1 : 0;
        return $this;
    }
}
