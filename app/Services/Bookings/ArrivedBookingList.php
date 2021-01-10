<?php

namespace App\Services\Bookings;

use App\Bookingrequestprovider;
use App\Bookingstatus;
use App\Plan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ArrivedBookingList
 * @package App\Services\Bookings
 */
class ArrivedBookingList extends AbstractBookingList
{
    /**
     * @return Collection
     */
    public function getAllBookings(): Collection
    {
        $bookingQuery = $this->getBookingQuery();

        $bookingQuery
            ->where('booking_status_id', Bookingstatus::BOOKING_STATUS_ARRIVED)
            ->where('plan_type', Plan::ONCEOFF);

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
            ->where('booking_request_provider.status', Bookingrequestprovider::STATUS_ACCEPTED);
    }
}