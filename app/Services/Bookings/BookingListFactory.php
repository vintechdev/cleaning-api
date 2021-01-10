<?php

namespace App\Services\Bookings;

use App\Bookingstatus;
use App\Services\Bookings\Interfaces\BookingListInterface;

/**
 * Class BookingListFactory
 * @package App\Services\Bookings
 */
class BookingListFactory
{
    /**
     * @param $status
     * @return BookingListInterface
     */
    public function create($status): BookingListInterface
    {
        switch ($status) {
            case Bookingstatus::BOOKING_STATUS_PENDING:
                return app(PendingBookingList::class);

            case Bookingstatus::BOOKING_STATUS_ACCEPTED:
                return app(AcceptedBookingList::class);

            case Bookingstatus::BOOKING_STATUS_REJECTED:
                return app(RejectedBookingList::class);

            case Bookingstatus::BOOKING_STATUS_COMPLETED:
                return app(CompletedBookingList::class);

            case Bookingstatus::BOOKING_STATUS_CANCELLED:
                return app(CancelledBookingList::class);

            case BookingStatus::BOOKING_STATUS_ARRIVED:
                return (app(ArrivedBookingList::class));

            default:
                throw new \InvalidArgumentException('Invalid booking status received');
        }
    }
}
