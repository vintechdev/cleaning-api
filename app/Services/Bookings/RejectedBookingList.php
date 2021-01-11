<?php

namespace App\Services\Bookings;

use App\Bookingrequestprovider;
use App\Bookingstatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class RejectedBookingList
 * @package App\Services\Bookings
 */
class RejectedBookingList extends AbstractBookingList
{
    /**
     * @return Collection
     */
    public function getAllBookings(): Collection
    {
        $bookingQuery = $this->getBookingQuery();

        $bookingQuery
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

    /**
     * @return Builder
     */
    protected function getProviderBookingQuery(): Builder
    {
        return parent::getProviderBookingQuery()
            ->where('booking_request_providers.status', Bookingrequestprovider::STATUS_REJECTED);
    }

    /**
     * @return Builder
     */
    protected function getUserBookingQuery(): Builder
    {
        return parent::getUserBookingQuery()
            ->where('booking_status_id', Bookingstatus::BOOKING_STATUS_REJECTED);
    }
}