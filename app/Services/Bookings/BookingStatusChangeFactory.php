<?php

namespace App\Services\Bookings;

use App\Exceptions\Booking\InvalidBookingStatusException;
use App\Services\Bookings\Interfaces\BookingStatusChangeStrategyInterface;

/**
 * Class BookingStatusChangeFactory
 * @package App\Services\Bookings
 */
class BookingStatusChangeFactory
{
    public function create(string $status): BookingStatusChangeStrategyInterface
    {
        switch ($status) {
            case 'rejected':
                return app(RejectBookingStrategy::class);
            case 'accepted':
                return app(AcceptBookingStrategy::class);
            case 'cancelled':
                return app(CancelBookingStrategy::class);
            case 'arrived':
                return app(ArriveBookingStrategy::class);
            case 'completed':
                return app(CompleteBookingStrategy::class);
            default:
                throw new InvalidBookingStatusException('Invalid booking status received');
        }
    }
}