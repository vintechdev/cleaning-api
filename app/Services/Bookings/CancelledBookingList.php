<?php

namespace App\Services\Bookings;

use App\Bookingstatus;
use App\Plan;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CancelledBookingList
 * @package App\Services\Bookings
 */
class CancelledBookingList extends AbstractBookingList
{
    /**
     * @return Collection
     */
    public function getAllBookings(): Collection
    {
        $bookingQuery = $this->getBookingQuery();

        $bookingQuery
            ->where('booking_status_id', Bookingstatus::BOOKING_STATUS_CANCELLED);

        $from = $this->getFrom();
        $to = $this->getTo();
        if ($from && $to) {
            $bookingQuery
                ->where('booking_date', '>=' ,$from->format('Y-m-d'))
                ->where('booking_date', '<=', $to->format('Y-m-d'));
        }

        return $bookingQuery->orderBy('booking_date', 'desc')->get('bookings.*');
    }
}