<?php

namespace App\Services\Bookings;

use App\Bookingrequestprovider;
use App\Bookingstatus;
use App\Plan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class AcceptedBookingList
 * @package App\Services\Bookings
 */
class AcceptedBookingList extends AbstractBookingList
{
    /**
     * @return Collection
     */
    public function getAllBookings(): Collection
    {
        $bookingQuery = $this->getBookingQuery();
        $bookingQuery
            ->where('booking_status_id', Bookingstatus::BOOKING_STATUS_ACCEPTED);

        if (!$this->getTo()) {
            throw new \InvalidArgumentException('There should be an end date specified for accepted bookings.');
        }

        $bookingQuery
            ->leftJoin('events', 'bookings.event_id', '=', 'events.id')
            ->where(function ($query) {
                $query
                    ->where(function($query) {
                        $query
                            ->where('bookings.plan_type', Plan::ONCEOFF)
                            ->where('parent_booking_id', null)
                            ->where('booking_date', '>=' ,$this->getfrom()->format('Y-m-d'))
                            ->where('booking_date', '<=', $this->getTo()->format('Y-m-d'));
                    })
                    ->orWhere(function($query) {
                        $query
                            ->where('booking_date', '<=', $this->getTo()->format('Y-m-d'))
                            ->where(function($query) {
                                $query
                                    ->where('events.end_date', null)
                                    ->orWhere('events.end_date', '>=', $this->getFrom()->format('Y-m-d 00:00:00'));
                            });
                    });
            });

        return $bookingQuery->orderBy('booking_date', 'desc')->get('bookings.*');
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
