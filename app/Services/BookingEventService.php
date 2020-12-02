<?php

namespace App\Services;

use App\Booking;
use App\Event;
use App\Exceptions\Booking\Factory\RecurringPatternFactoryException;
use App\Factory\Booking\RecurringPatternFactory;
use App\Plan;
use App\RecurringPattern;
use App\Traits\RecurringPatternTrait;

/**
 * Class BookingEventService
 * @package App\Services
 */
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
     * @param Booking $booking
     * @return Event
     */
    public function createBookingEvent(Booking $booking): Event
    {
        $event = new Event();
        $event->start_date = $booking->getStartDate();
        $event = $this->eventService->createEvent($event);
        $booking->event()->associate($event)->save();

        try {
            /** @var RecurringPatternTrait $pattern */
            $pattern = $this->getRecurringPatternFromPlan($event, $booking->plan_type);
        } catch (RecurringPatternFactoryException $exception) {
            return $event;
        }

        // TODO: Move the logic below to a repository
        $pattern->save();
        $recurringPattern = $pattern
            ->recurringPattern()
            ->make();
        $recurringPattern->separation_count = $pattern->getSeparationCount();
        $recurringPattern->event()->associate($event)->save();

        return $event;
    }

    /**
     * @param Event $event
     * @param int $planId
     * @return RecurringPattern
     */
    private function getRecurringPatternFromPlan(Event $event, int $planId): RecurringPattern
    {
        if (!Plan::isValidPlan($planId)) {
            throw new \RuntimeException('Invalid plan id received');
        }

        return $this->recurringPatternFactory->create($planId, $event);
    }
}