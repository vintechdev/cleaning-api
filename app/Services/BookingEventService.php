<?php

namespace App\Services;

use App\Booking;
use App\Event;
use App\Exceptions\Booking\Factory\RecurringPatternFactoryException;
use App\Factory\Booking\RecurringPatternFactory;
use App\Interfaces\RecurringDateInterface;
use App\Plan;
use App\RecurringBooking;
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
     * @var RecurringBookingService
     */
    private $recurringBookingService;

    /**
     * BookingEventService constructor.
     * @param RecurringPatternFactory $recurringPatternFactory
     * @param EventService $eventService
     * @param RecurringPatternService $recurringPatternService
     * @param RecurringBookingService $recurringBookingService
     */
    public function __construct(
        RecurringPatternFactory $recurringPatternFactory,
        EventService $eventService,
        RecurringPatternService $recurringPatternService,
        RecurringBookingService $recurringBookingService
    ) {
        $this->recurringPatternFactory = $recurringPatternFactory;
        $this->eventService = $eventService;
        $this->recurringPatternService = $recurringPatternService;
        $this->recurringBookingService = $recurringBookingService;
    }

    /**
     * @param Booking $booking
     * @return Event|null
     */
    public function createBookingEvent(Booking $booking): ?Event
    {
        if ($booking->getPlanType() === Plan::ONCEOFF) {
            return null;
        }
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

        return $this->removeAllDatesWhichHasRecurringBooking($booking, $dates);
    }

    /**
     * @param Booking $booking
     * @param Carbon $fromDate
     * @param Carbon $toDate
     * @return array
     */
    public function listBookingDatesBetween(Booking $booking, Carbon $fromDate, Carbon $toDate): array
    {
        if ($booking->getPlanType() === Plan::ONCEOFF) {
            $dates = $this->getOnceOffBookingDate($booking, $fromDate, $toDate);
        } else {
            $dates = $this
                ->recurringPatternService
                ->getRecurringDateTimeBetween($fromDate, $toDate, $booking->getEvent());
        }

        return $this->removeAllDatesWhichHasRecurringBooking($booking, $dates);
    }

    /**
     * @param Booking $booking
     * @param Carbon $recurringDate
     * @return bool
     */
    public function isValidRecurringDate(Booking $booking, Carbon $recurringDate): bool
    {
        if (!$booking->isRecurring()) {
            return false;
        }
        return $this->recurringPatternService->isValidRecurringDate($booking->getEvent(), $recurringDate);
    }

    /**
     * @param Booking $booking
     * @param array $dates
     * @return array
     */
    private function removeAllDatesWhichHasRecurringBooking(Booking $booking, array $dates)
    {
        if (!$dates) {
            return [];
        }

        if (!$booking->getEvent()) {
            return $this->formatReturnDates($booking, $dates);
        }

        $recurringBookings = $this
            ->recurringBookingService
            ->findByEventAndDates($booking->getEvent(), $dates);

        if (!$recurringBookings->count()) {
            return $this->formatReturnDates($booking, $dates);
        }

        /** @var RecurringBooking $recurringBooking */
        foreach ($recurringBookings as $recurringBooking) {
            $key = $this->getDateKeyByValue($dates, $recurringBooking->getRecurredDate());
            unset($dates[$key]);
        }

        return $this->formatReturnDates($booking, $dates);
    }

    /**
     * @param array $dates
     * @param Carbon $lookUpDate
     * @return int|null
     */
    private function getDateKeyByValue(array $dates, Carbon $lookUpDate): ?int
    {
        /** @var Carbon $date */
        foreach ($dates as $key => $date) {
            if ($date->equalTo($lookUpDate)) {
                return $key;
            }
        }

        return null;
    }

    /**
     * @param Booking $booking
     * @param array $dates
     * @return array
     */
    private function formatReturnDates(Booking $booking, array $dates): array
    {
        $returnDates = [];
        /** @var Carbon $date */
        foreach ($dates as $date) {
            $from = $date->format('d-m-Y H:i:s');
            $to = Booking::calculateFinalBookingDateTime($date, $booking->getFinalHours());
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
     * @param null $toDateTime
     * @return array|Carbon[]
     */
    private function getOnceOffBookingDate(Booking $booking, Carbon $fromDateTime = null, $toDateTime = null): array
    {
        if ($booking->getEvent()) {
            $date = $booking->getEvent()->getStartDateTime();
        } else {
            $date = $booking->getStartDate();
        }

        if (!$date || $fromDateTime->greaterThan($date) || $date->greaterThan($toDateTime)) {
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