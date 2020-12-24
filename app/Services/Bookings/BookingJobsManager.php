<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Repository\BookingReqestProviderRepository;
use App\Services\BookingEventService;
use App\User;
use Carbon\Carbon;

/**
 * Class BookingJobsManager
 * @package App\Services\Bookings
 */
class BookingJobsManager
{
    /**
     * @var BookingEventService
     */
    private $bookingEventService;

    /**
     * @var BookingReqestProviderRepository
     */
    private $bookingRequestProviderRepo;

    /**
     * BookingManager constructor.
     * @param BookingEventService $bookingEventService
     * @param BookingReqestProviderRepository $bookingReqestProviderRepository
     */
    public function __construct(
        BookingEventService $bookingEventService,
        BookingReqestProviderRepository $bookingReqestProviderRepository
    ) {
        $this->bookingEventService = $bookingEventService;
        $this->bookingRequestProviderRepo = $bookingReqestProviderRepository;
    }

    /**
     * @param User $user
     * @param Carbon|null $fromDate
     * @param int $totalPeriod
     * @return array
     */
    public function getAllPastBookingJobsByUser(User $user, Carbon $fromDate = null, $totalPeriod = 30): array
    {
        if (!$fromDate) {
            $fromDate = (Carbon::now())->subDays($totalPeriod);
        } else if ($fromDate->greaterThanOrEqualTo(Carbon::now())) {
            return [];
        }

        $toDate = (clone $fromDate)->addDays($totalPeriod);
        if ($toDate->greaterThanOrEqualTo(Carbon::now())) {
            $toDate = Carbon::now();
        }

        return $this->getAllJobsBetweenDates($user, $fromDate, $toDate);
    }

    /**
     * @param User $user
     * @param Carbon|null $fromDate
     * @param int $totalPeriod in days
     * @return array
     */
    public function getAllFutureBookingJobsByUser(User $user, Carbon $fromDate = null, $totalPeriod = 30): array
    {
        $fromDate = ($fromDate && $fromDate->greaterThanOrEqualTo(Carbon::now())) ? $fromDate : Carbon::now();
        $toDate = (clone $fromDate)->addDays($totalPeriod);
        return $this->getAllJobsBetweenDates($user, $fromDate, $toDate);
    }

    /**
     * @param User $user
     * @param Carbon|null $fromDate
     * @param int $totalPeriod in days
     * @return array
     */
    public function getAllBookingJobsByUser(User $user, Carbon $fromDate = null, $totalPeriod = 30): array
    {
        $bookings = Booking::findByUserId($user->id);

        if (!$bookings->count()) {
            return [];
        }

        if (!$fromDate) {
            $fromDate = Carbon::now();
        }
        $toDate = (clone $fromDate)->addDays($totalPeriod);
        return $this->getAllJobsBetweenDates($user, $fromDate, $toDate);
    }

    /**
     * @param User $user
     * @param Carbon $from
     * @param Carbon $to
     * @return array
     */
    private function getAllJobsBetweenDates(User $user, Carbon $from, Carbon $to): array
    {
        $jobs = [];
        $bookings = Booking::findByUserId($user->id);
        if (!$bookings->count()) {
            return $jobs;
        }

        $bookingDates = [];
        /** @var Booking $booking */
        foreach ($bookings->get() as $booking) {
            $providerDetails = $this->getProviderDetails($booking);
            $serviceInfo = $booking->getBookingServices();
            $dates = $this
                ->bookingEventService
                ->listBookingDatesBetween($booking, $from, $to);

            foreach ($dates as $date) {
                $date['booking_id'] = $booking->id;
                $date['is_recurring'] = $booking->isRecurring();
                $date['booking_status'] = $booking->getStatus();
                $date['providers'] = $providerDetails;
                $date['service'] = $serviceInfo;
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
     * @param Booking $booking
     * @return array
     */
    private function getProviderDetails(Booking $booking): array
    {
        $pendingProviders = $this
            ->bookingRequestProviderRepo
            ->getBookingPendingProvidersDetails($booking->id);

        $pendingDetails = [];
        foreach ($pendingProviders as $provider) {
            $pendingDetails['id'] = $provider['provider_first_name'] . ' ' . $provider['provider_last_name'];
        }

        $acceptedProviders = $this
            ->bookingRequestProviderRepo
            ->getBookingAccptedProvidersDetails($booking->id);

        $acceptedDetails = [];

        foreach ($acceptedProviders as $provider) {
            $acceptedDetails['id'] = $provider['provider_first_name'] . ' ' . $provider['provider_last_name'];
        }

        return [
            'pending' => $pendingDetails,
            'accepted' => $acceptedDetails
        ];
    }
}
