<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Services\BookingEventService;
use App\User;
use Carbon\Carbon;

/**
 * Class BookingManager
 * @package App\Services\Bookings
 */
class BookingJobsManager
{
    /**
     * @var BookingEventService
     */
    private $bookingEventService;

    /**
     * BookingManager constructor.
     * @param BookingEventService $bookingEventService
     */
    public function __construct(BookingEventService $bookingEventService)
    {
        $this->bookingEventService = $bookingEventService;
    }

    /**
     * @param User $user
     * @return array
     */
    public function getAllPastBookingJobsByUser(User $user): array
    {
        // TODO: fetch past booking dates from the table that stores the past dates
        return [];
    }

    /**
     * @param User $user
     * @param Carbon|null $fromDate
     * @param int $totalDays
     * @return array
     */
    public function getAllFutureBookingJobsByUser(User $user, Carbon $fromDate = null, $totalDays = 30): array
    {
        $fromDate = $fromDate ?: Carbon::now();
        $jobs = [];
        $bookings = Booking::findByUserId($user->id);
        if (!$bookings->count()) {
            return $jobs;
        }

        $bookingDates = [];
        /** @var Booking $booking */
        foreach ($bookings->get() as $booking) {
            $toDate = clone $fromDate;
            $dates = $this
                ->bookingEventService
                ->listBookingDatesBetween($booking, $fromDate, $toDate->addDays($totalDays));

            foreach ($dates as $date) {
                $date['booking_id'] = $booking->id;
                $bookingDates[] = $date;
            }
        }

        usort($bookingDates, function ($a, $b) {
            $aDate = Carbon::createFromFormat('d-m-Y H:i:s', $a['from']);
            $bDate = Carbon::createFromFormat('d-m-Y H:i:s', $b['from']);

            if ($bDate->lessThan($aDate)) {
                return true;
            }

            if ($bDate->equalTo($aDate)) {
                return ($b['booking_id'] <= $a['booking_id']);
            }

            return false;
        });

        return $bookingDates;
    }

    /**
     * @param User $user
     * @return array
     */
    public function getAllBookingJobsByUser(User $user): array
    {
        $bookings = Booking::findByUserId($user->id);

        if (!$bookings->count()) {
            return [];
        }

        $pastDates = $this->getAllPastBookingJobsByUser($user);
        $futureDates = $this->getAllFutureBookingJobsByUser($user);

        return array_merge($pastDates, $futureDates);
    }
}