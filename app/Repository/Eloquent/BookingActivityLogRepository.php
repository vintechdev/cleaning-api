<?php

namespace App\Repository\Eloquent;

use App\Bookingactivitylog;

/**
 * Class BookingActivityLogRepository
 * @package App\Repository\Eloquent
 */
class BookingActivityLogRepository extends AbstractBaseRepository
{
    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Bookingactivitylog::class;
    }
}