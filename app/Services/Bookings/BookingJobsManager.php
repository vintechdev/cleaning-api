<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Repository\BookingReqestProviderRepository;
use App\Services\BookingEventService;
use App\User;
use Carbon\Carbon;
use App\Bookingstatus;

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
    public function getAllPastBookingJobsByUser(User $user, Carbon $fromDate = null, $totalPeriod = 30, bool $isProvider=false): array
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

        return $this->getAllJobsBetweenDates($user, $fromDate, $toDate, $isProvider);
    }

    /**
     * @param User $user
     * @param Carbon|null $fromDate
     * @param int $totalPeriod in days
     * @return array
     */
    public function getAllFutureBookingJobsByUser(User $user, Carbon $fromDate = null, $totalPeriod = 30, bool $isProvider=false): array
    {
        $fromDate = ($fromDate && $fromDate->greaterThanOrEqualTo(Carbon::now())) ? $fromDate : Carbon::now();
        $toDate = (clone $fromDate)->addDays($totalPeriod);
        return $this->getAllJobsBetweenDates($user, $fromDate, $toDate, $isProvider);
    }

    /**
     * @param User $user
     * @param Carbon|null $fromDate
     * @param int $totalPeriod in days
     * @return array
     */
    public function getAllBookingJobsByUser(User $user, Carbon $fromDate = null, $totalPeriod = 30, bool $isProvider=false): array
    {
        if (!$fromDate) {
            $fromDate = Carbon::now();
        }
        $toDate = (clone $fromDate)->addDays($totalPeriod);
        return $this->getAllJobsBetweenDates($user, $fromDate, $toDate, $isProvider);
    }

    /**
     * @param User $user
     * @param Carbon $from
     * @param Carbon $to
     * @return array
     */
    private function getAllJobsBetweenDates(User $user, Carbon $from, Carbon $to, bool $isProvider=false): array
    {
        $jobs = [];

        if($isProvider){
            $bookings = Booking::with(['users','bookingServices','bookingServices.service'])->leftJoin('booking_request_providers','booking_request_providers.booking_id','=','bookings.id')->where('booking_request_providers.provider_user_id',$user->id);
        }else{
            $bookings = Booking::with(['bookingServices','bookingServices.service'])->where('bookings.user_id',$user->id);//->findByUserId($user->id);
        }
        if (!$bookings->count()) {
            return $jobs;
        }
      //  dd($bookings);

        $bookingDates = [];
        /** @var Booking $booking */
        foreach ($bookings->get('bookings.*') as $booking) {
            $providerDetails = $this->getProviderDetails($booking);
            $serviceInfo = $booking->getBookingServicesArr();
            $dates = $this
                ->bookingEventService
                ->listBookingDatesBetween($booking, $from, $to);
            $users = $booking->getUserDetails();
            foreach ($dates as $date) {
                $date['booking_id'] = $booking->id;
                $date['is_recurring_item'] = $booking->isRecurring();
                $date['booking_status'] = $booking->getStatus();
                $date['providers'] = $providerDetails;
                $date['booking_service'] = $serviceInfo;
                $date['booking_status_name'] = Bookingstatus::getStatusNameById( $booking->booking_status_id);
                $date['user'] = $users;
                $date['booking']=$booking;
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

    public function getBookingDetailsByProvider(User $user, $id){
        
        $jobs = [];
        $bookings = Booking::with(['users','bookingServices','bookingServices.service','address','bookingstatus'])->where('bookings.id',$id)->get('bookings.*');

        if(!$bookings->count()){
            return $jobs;
        }
        return $bookings->toArray();

    }
}
