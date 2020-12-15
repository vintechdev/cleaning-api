<?php

namespace App\Repository\Eloquent;

use App\Event;

/**
 * Class EventServiceRepository
 * @package App\Repository\Eloquent
 */
class EventRepository extends AbstractBaseRepository
{
    protected function getModelClass(): string
    {
        return Event::class;
    }
}