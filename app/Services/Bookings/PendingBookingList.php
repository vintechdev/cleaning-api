<?php

namespace App\Services\Bookings;

use App\Bookingstatus;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PendingBookingList
 * @package App\Services\Bookings
 */
class PendingBookingList extends AbstractBookingList
{
    /**
     * @return Collection
     */
    public function getAllBookings(): Collection
    {
        $bookingQuery = $this->getBookingQuery()
            ->where('booking_status_id', Bookingstatus::BOOKING_STATUS_PENDING)
            ->where('parent_booking_id', null);

        $from = $this->getFrom();
        $to = $this->getTo();
        if ($from && $to) {
            $bookingQuery
                ->where('booking_date', '>=' ,$from->format('Y-m-d'))
                ->where('booking_date', '<=', $to->format('Y-m-d'));
        }

        return $bookingQuery->orderBy('booking_date', 'desc')->get();
    }
}
