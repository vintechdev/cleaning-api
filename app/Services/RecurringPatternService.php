<?php

namespace App\Services;

use App\Event;
use App\Interfaces\RecurringDateInterface;
use App\Repository\Eloquent\RecurringPatternRepository;
use App\WeeklyRecurringPattern;
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
    public function getRecurringDateTimes(Event $event, int $limit = 10, int $offset = 1)
    {
        $event = Event::find(35);
        /** @var Collection $recurringPattern */
        $recurringPatterns = $this->recurringPatternRepository->findByEvent($event);

        /** @var RecurringDateInterface $recurringPatternable */
        $recurringPatternable = $recurringPatterns->first()->recurringPatternable;

        $date = $recurringPatternable->getDateByOffset($offset);
        $dates = [$date];

        for ($i = 1; $i < $limit; $i++) {
            $date = $recurringPatternable->getNextValidDateRelativeTo($date);
            $dates[] = clone $date;
        }

        return $dates;
    }
}