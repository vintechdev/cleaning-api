<?php

namespace App\Services;

use App\Event;
use App\Interfaces\RecurringDateInterface;
use App\Repository\Eloquent\RecurringPatternRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class RecurringPatternService
 * @package App\Services
 */
class RecurringPatternService
{
    /**
     * @var RecurringPatternRepository
     */
    private $recurringPatternRepository;

    /**
     * RecurringPatternService constructor.
     * @param RecurringPatternRepository $recurringPatternRepository
     */
    public function __construct(RecurringPatternRepository $recurringPatternRepository)
    {
        $this->recurringPatternRepository = $recurringPatternRepository;
    }

    /**
     * @param Event $event
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getRecurringDateTimes(Event $event, int $limit = 10, int $offset = 1): array
    {
        $recurringPatternable = $this->getRecurringPatternFromEvent($event);

        if (!$recurringPatternable) {
            return [];
        }

        $dates = [];
        $date = $recurringPatternable->getDateByOffset($offset);
        $dates[] = clone $date;

        for ($i = 1; $i < $limit; $i++) {
            $date = $recurringPatternable->getNextValidDateRelativeTo($date);
            $dates[] = clone $date;
        }

        return $dates;
    }

    /**
     * Returns all recurring date after $relativeDate
     * @param Carbon $relativeDate
     * @param Event $event
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getRecurringDateTimesPostDateTime(Carbon $relativeDate, Event $event, $limit = 10, int $offset = 1): array
    {
        $recurringPatternable = $this->getRecurringPatternFromEvent($event);
        if (!$recurringPatternable) {
            return [];
        }

        $dates = [];
        $date = $relativeDate;

        $j = 0;
        if ($offset > 1) {
            $date = $recurringPatternable->getDateByOffset($offset, $relativeDate);
            $dates[] = $date;
            $j++;
        }

        for ($i = $j; $i < $limit; $i++) {
            $date = $recurringPatternable->getNextValidDateRelativeTo($date);
            $dates[] = clone $date;
        }

        return $dates;
    }

    /**
     * @param Carbon $fromDate
     * @param Carbon $toDate
     * @param Event $event
     * @return array
     */
    public function getRecurringDateTimeBetween(Carbon $fromDate, Carbon $toDate, Event $event): array
    {
        $recurringPatternable = $this->getRecurringPatternFromEvent($event);
        if (!$recurringPatternable) {
            return [];
        }

        $date = $fromDate;
        $dates = [];

        while (true) {
            $date = $recurringPatternable->getNextValidDateRelativeTo($date);
            if (!$toDate->greaterThan($date)) {
                break;
            }
            $dates[] = clone $date;
        }

        return $dates;
    }

    /**
     * @param Event $event
     * @param Carbon $date
     * @return bool
     */
    public function isValidRecurringDate(Event $event, Carbon $date): bool
    {
        $recurringPatternable = $this->getRecurringPatternFromEvent($event);
        if (!$recurringPatternable) {
            return false;
        }

        return $recurringPatternable->isValidRecurringDate($date);
    }

    /**
     * @param Event $event
     * @return RecurringDateInterface|null
     */
    private function getRecurringPatternFromEvent(Event $event): ?RecurringDateInterface
    {
        /** @var Collection $recurringPattern */
        $recurringPatterns = $this->recurringPatternRepository->findByEvent($event);

        if (!$recurringPatterns->count()) {
            return null;
        }

        return $recurringPatterns->first()->recurringPatternable;
    }
}