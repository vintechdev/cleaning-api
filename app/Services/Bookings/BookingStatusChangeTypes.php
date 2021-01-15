<?php

namespace App\Services\Bookings;

/**
 * Class BookingStatusChangeTypes
 * @package App\Services\Bookings
 */
class BookingStatusChangeTypes
{
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_ARRIVED = 'arrived';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCEL_AFTER = 'cancel_after';

    /**
     * @return string[]
     */
    public static function getAll(): array
    {
        return [
            self::STATUS_ACCEPTED,
            self::STATUS_REJECTED,
            self::STATUS_CANCELLED,
            self::STATUS_ARRIVED,
            self::STATUS_COMPLETED,
            self::STATUS_CANCEL_AFTER
        ];
    }
}