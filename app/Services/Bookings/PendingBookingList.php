<?php

namespace App\Services\Bookings;

use App\Bookingrequestprovider;
use App\Bookingstatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

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
            ->where('parent_booking_id', null)
            ->where('booking_request_providers.id', '<>', null);

        $from = $this->getFrom();
        $to = $this->getTo();
        if ($from && $to) {
            $bookingQuery
                ->where('booking_date', '>=' ,$from->format('Y-m-d'))
                ->where('booking_date', '<=', $to->format('Y-m-d'));
        }

        return $bookingQuery->orderBy('booking_date', 'desc')->get('bookings.*');
    }

    /**
     * @return Builder
     */
    protected function getProviderBookingQuery(): Builder
    {
        return parent::getProviderBookingQuery()
            ->where('booking_request_providers.status', '<>', Bookingrequestprovider::STATUS_REJECTED);
    }
}
