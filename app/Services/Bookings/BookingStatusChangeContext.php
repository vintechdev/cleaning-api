<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Exceptions\Booking\RecurringBookingStatusChangeException;
use App\Exceptions\Booking\UnauthorizedAccessException;
use App\Services\Bookings\Interfaces\BookingStatusChangeStrategyInterface;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Class BookingStatusChangeContext
 * @package App\Services\Bookings
 */
class BookingStatusChangeContext
{
    /**
     * @var BookingStatusChangeStrategyInterface
     */
    private $bookingStatusChangeStrategy;

    /**
     * BookingStatusChangeContext constructor.
     * @param BookingStatusChangeStrategyInterface $bookingStatusChangeStrategy
     */
    public function __construct(BookingStatusChangeStrategyInterface $bookingStatusChangeStrategy)
    {
        $this->bookingStatusChangeStrategy = $bookingStatusChangeStrategy;
    }

    /**
     * @param Booking $booking
     * @param User $user
     * @param Carbon|null $recurredDate
     * @return Booking | null
     * @throws RecurringBookingStatusChangeException
     * @throws UnauthorizedAccessException
     */
    public function changeStatus(Booking $booking, User $user, Carbon $recurredDate = null): ?Booking
    {
        $booking = $this->bookingStatusChangeStrategy->changeStatus($booking, $user, $recurredDate);
        Log::info('Booking status changed.');
        return $booking;
    }
}
