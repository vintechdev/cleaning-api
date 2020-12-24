<?php

namespace App\Services\Bookings\Builder;

use App\Exceptions\Booking\InvalidBookingStatusException;
use App\Services\Bookings\BookingStatusChangeContext;
use App\Services\Bookings\BookingStatusChangeFactory;

/**
 * Class BookingStatusChangeContextBuilder
 * @package App\Services\Bookings\Builder
 */
class BookingStatusChangeContextBuilder
{
    /**
     * @var BookingStatusChangeFactory
     */
    private $factory;

    /**
     * BookingStatusChangeContextBuilder constructor.
     * @param BookingStatusChangeFactory $bookingStatusChangeFactory
     */
    public function __construct(BookingStatusChangeFactory $bookingStatusChangeFactory)
    {
        $this->factory = $bookingStatusChangeFactory;
    }

    /**
     * @param string $status
     * @param array $parameters
     * @return BookingStatusChangeContext
     * @throws InvalidBookingStatusException
     */
    public function buildContext(string $status, array $parameters): BookingStatusChangeContext
    {
        $strategy = $this->factory->create($status, $parameters);
        return new BookingStatusChangeContext($strategy);
    }
}