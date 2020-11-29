<?php

namespace App\Services;

use App\Dto\BookingEventDto;
use App\Event;
use App\Exceptions\Booking\Factory\RecurringPatternFactoryException;
use App\Factory\Booking\RecurringPatternFactory;
use App\Plan;

class BookingEventService
{
    /**
     * @var RecurringPatternFactory
     */
    private $recurringPatternFactory;

    /**
     * @var EventService
     */
    private $eventService;

    /**
     * BookingEventService constructor.
     * @param RecurringPatternFactory $recurringPatternFactory
     * @param EventService $eventService
     */
    public function __construct(
        RecurringPatternFactory $recurringPatternFactory,
        EventService $eventService
    ) {
        $this->recurringPatternFactory = $recurringPatternFactory;
        $this->eventService = $eventService;
    }

    /**
     * @param BookingEventDto $bookingEventDto
     * @return Event
     */
    public function createBookingEvent(BookingEventDto $bookingEventDto): Event
    {
        $event = new Event();
        $event->startDate = $bookingEventDto->getStartDate();
        $this->setRecurringPatternFromPlan($event, $bookingEventDto->getPlanId());
        return $this->eventService->createEvent($event);
    }

    /**
     * @param Event $event
     * @param int $planId
     * @return bool
     */
    public function setRecurringPatternFromPlan(Event $event, int $planId): bool
    {
        if (!Plan::isValidPlan($planId)) {
            throw new \RuntimeException('Invalid plan id received');
        }

        try {
            $event->recurringPattern = $this->recurringPatternFactory->create($planId, $event);
        } catch (RecurringPatternFactoryException $exception) {
            // Don't do anything
        }

        return true;
    }
}