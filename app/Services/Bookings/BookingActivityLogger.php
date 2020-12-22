<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Http\Resources\Bookingactivitylog;
use App\Repository\Eloquent\BookingActivityLogRepository;
use App\User;

/**
 * Class BookingActivityLogger
 * @package App\Services\Bookings
 */
class BookingActivityLogger
{
    /**
     * @var BookingActivityLogRepository
     */
    private $activityLogRepository;

    /**
     * BookingActivityLogger constructor.
     * @param BookingActivityLogRepository $activityLogRepository
     */
    public function __construct(BookingActivityLogRepository $activityLogRepository)
    {
        $this->activityLogRepository = $activityLogRepository;
    }

    /**
     * @param Booking $booking
     * @param User $user
     * @param string $action
     * @param string $detail
     * @return Bookingactivitylog
     */
    public function addLog(Booking $booking, User $user, string $action, string $detail)
    {
        return $this->activityLogRepository->create([
            'booking_id' => $booking->getId(),
            'user_id' => $user->getId(),
            'status' => $booking->getStatus(),
            'is_recurring' => $booking->isRecurring(),
            'action' => $action,
            'detail' => $detail,
            'booking_date' => $booking->getStartDate(),
            'booking_postcode' => $booking->getPostCode()
        ]);
    }
}