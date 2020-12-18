<?php

namespace App\Services;

use App\Event;

/**
 * Class EventService
 * @package App\Services
 */
class EventService
{
    /**
     * @param Event $event
     * @return Event
     */
    public function createEvent(Event $event): Event
    {
        // Add any logic around saving events here.
        $event->save();
        return $event;
    }
}
