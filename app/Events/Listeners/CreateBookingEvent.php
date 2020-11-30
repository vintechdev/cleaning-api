<?php

namespace App\Events\Listeners;

use App\Events\BookingCreated;
use App\Services\BookingEventService;

/**
 * Class CreateBookingEvent
 * @package App\Events\Listeners
 */
class CreateBookingEvent
{
    /**
     * @var BookingEventService
     */
    private $bookingEventService;

    /**
     * CreateBookingEvent constructor.
     * @param BookingEventService $bookingEventService
     */
    public function __construct(BookingEventService $bookingEventService)
    {
        $this->bookingEventService = $bookingEventService;
    }

    /**
     * @param BookingCreated $bookingCreated
     */
    public function handle(BookingCreated $bookingCreated): void
    {
        $this->bookingEventService->createBookingEvent($bookingCreated->getBooking());
    }
}