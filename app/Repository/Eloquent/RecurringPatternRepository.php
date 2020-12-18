<?php

namespace App\Repository\Eloquent;

use App\Event;
use App\RecurringPattern;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class RecurringPatternRepository
 * @package App\Repository\Eloquent
 */
class RecurringPatternRepository extends AbstractBaseRepository
{
    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return RecurringPattern::class;
    }

    /**
     * @param Event $event
     * @return RecurringPattern|null
     */
    public function findByEvent(Event $event): ?Collection
    {
        return RecurringPattern::where('event_id', $event->getId())->get();
    }
}