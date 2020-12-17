<?php

namespace App\Services;

use App\Booking;
use App\Event;
use App\Exceptions\Booking\Factory\RecurringPatternFactoryException;
use App\Factory\Booking\RecurringPatternFactory;
use App\Interfaces\RecurringDateInterface;
use App\Plan;
use App\RecurringPattern;
use App\Traits\RecurringPatternTrait;
use Carbon\Carbon;

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
     * @var RecurringPatternService
     */
    private $recurringPatternService;

    /**
     * BookingEventService constructor.
     * @param RecurringPatternFactory $recurringPatternFactory
     * @param EventService $eventService
     */
    public function __construct(
        RecurringPatternFactory $recurringPatternFactory,
        EventService $eventService,
        RecurringPatternService $service
    ) {
        $this->recurringPatternFactory = $recurringPatternFactory;
        $this->eventService = $eventService;
        $this->recurringPatternService = $service;
    }

    /**
     * @param Booking $booking
     * @return Event
     */
    public function createBookingEvent(Booking $booking): Event
    {
        $event = new Event();
        $event->start_date = $booking->getStartDate();
        $event->end_date = $booking->getEndDate();
        $event = $this->eventService->createEvent($event);
        $booking->event()->associate($event)->save();

        try {
            /** @var RecurringPatternTrait $pattern */
            $pattern = $this->getRecurringPatternFromPlan($event, $booking);
        } catch (RecurringPatternFactoryException $exception) {
            return $event;
        }

        // TODO: Move the logic below to a repository
        $pattern->save();

        /** @var RecurringPattern $recurringPattern */
        $recurringPattern = $pattern
            ->recurringPattern()
            ->make();
        $recurringPattern->setSeparationCountFromPlan($booking->getPlanType());
        $recurringPattern->event()->associate($event)->save();
        return $event;
    }

    /**
     * @param Booking $booking
     * @param Carbon|null $fromDateTime
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function listBookingDates(
        Booking $booking,
        Carbon $fromDateTime = null,
        int $limit = 10,
        int $offset = 1
    ): array {
        if (is_null($fromDateTime)) {
            $fromDateTime = Carbon::now();
        }

        if ($booking->getPlanType() === Plan::ONCEOFF) {
            $dates = $this->getOnceOffBookingDate($booking, $fromDateTime);
        } else {
            $dates = $this
                ->recurringPatternService
                ->getRecurringDateTimesPostDateTime($fromDateTime, $booking->getEvent(), $limit, $offset);
        }

        return $this->formatReturnDates($booking, $dates);
    }

    public function listBookingDatesBetween(Booking $booking, Carbon $fromDate, Carbon $toDate): array
    {
        if ($booking->getPlanType() === Plan::ONCEOFF) {
            $dates = $this->getOnceOffBookingDate($booking, $fromDate);
        } else {
            $dates = $this
                ->recurringPatternService
                ->getRecurringDateTimeBetween($fromDate, $toDate, $booking->getEvent());
        }
        return $this->formatReturnDates($booking, $dates);
    }

    /**
     * @param array $dates
     * @return array
     */
    private function formatReturnDates(Booking $booking, array $dates): array
    {
        $returnDates = [];
        /** @var Carbon $date */
        foreach ($dates as $date) {
            $from = $date->format('d-m-Y H:i:s');
            $to = Booking::getFinalBookingDateTime($date, $booking->getFinalHours());
            if ($to) {
                $to = $to->format('d-m-Y H:i:s');
            }
            $returnDates[] = ['from' => $from, 'to' => $to];
        }

        return $returnDates;
    }

    /**
     * @param Booking $booking
     * @param Carbon|null $fromDateTime
     * @return array|Carbon[]
     */
    private function getOnceOffBookingDate(Booking $booking, Carbon $fromDateTime = null): array
    {
        /** @var Carbon $date */
        $date = $booking->event->start_date;

        if (!$date || $fromDateTime->greaterThan($date)) {
            return [];
        }

        return [$date];
    }

    /**
     * @param Event $event
     * @param Booking $booking
     * @return RecurringPattern
     */
    private function getRecurringPatternFromPlan(Event $event, Booking $booking): RecurringDateInterface
    {
        if (!Plan::isValidPlan($booking->getPlanType())) {
            throw new \RuntimeException('Invalid plan id received');
        }

        return $this->recurringPatternFactory->create($booking->getPlanType(), $event);
    }
}