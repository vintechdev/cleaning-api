<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Repository\BookingReqestProviderRepository;
use App\Repository\BookingServiceRepository;
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
     * @var BookingServiceRepository
     */
    private $bookingServiceRepo;

    /**
     * BookingManager constructor.
     * @param BookingEventService $bookingEventService
     * @param BookingReqestProviderRepository $bookingReqestProviderRepository
     * @param BookingServiceRepository $bookingServiceRepository
     */
    public function __construct(
        BookingEventService $bookingEventService,
        BookingReqestProviderRepository $bookingReqestProviderRepository,
        BookingServiceRepository $bookingServiceRepository
    ) {
        $this->bookingEventService = $bookingEventService;
        $this->bookingRequestProviderRepo = $bookingReqestProviderRepository;
        $this->bookingServiceRepo = $bookingServiceRepository;
    }

    /**
     * @param User $user
     * @param Carbon|null $fromDate
     * @param int $totalPeriod
     * @return array
     */
    public function getAllPastBookingJobsByUser(User $user, Carbon $fromDate = null, $totalPeriod = 30): array
    {
        // TODO: fetch past booking dates from the table that stores the past dates
        return [];
    }

    /**
     * @param User $user
     * @param Carbon|null $fromDate
     * @param int $totalPeriod in days
     * @return array
     */
    public function getAllFutureBookingJobsByUser(User $user, Carbon $fromDate = null, $totalPeriod = 30): array
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
            $providerDetails = $this->getProviderDetails($booking);
            $serviceInfo = $this->bookingServiceRepo->getServiceDetails($booking->id);

            $toDate = clone $fromDate;
            $dates = $this
                ->bookingEventService
                ->listBookingDatesBetween($booking, $fromDate, $toDate->addDays($totalPeriod));

            foreach ($dates as $date) {
                $date['booking_id'] = $booking->id;
                $date['providers'] = $providerDetails;
                $date['service'] = $serviceInfo ? $serviceInfo[0] : [];
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

        $pastDates = $this->getAllPastBookingJobsByUser($user, $fromDate, $totalPeriod);
        $futureDates = $this->getAllFutureBookingJobsByUser($user, $fromDate, $totalPeriod);

        return array_merge($pastDates, $futureDates);
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