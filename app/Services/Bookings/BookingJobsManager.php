<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Repository\BookingReqestProviderRepository;
use App\Services\BookingEventService;
use App\Services\RecurringBookingService;
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
     * @var RecurringBookingService
     */
    private $recurringBookingService;

    /**
     * @var BookingService
     */
    private $bookingService;

    /**
     * BookingManager constructor.
     * @param BookingEventService $bookingEventService
     * @param BookingReqestProviderRepository $bookingReqestProviderRepository
     * @param RecurringBookingService $recurringBookingService
     * @param BookingService $bookingService
     */
    public function __construct(
        BookingEventService $bookingEventService,
        BookingReqestProviderRepository $bookingReqestProviderRepository,
        RecurringBookingService $recurringBookingService,
        BookingService $bookingService
    ) {
        $this->bookingEventService = $bookingEventService;
        $this->bookingRequestProviderRepo = $bookingReqestProviderRepository;
        $this->recurringBookingService = $recurringBookingService;
        $this->bookingService = $bookingService;
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
     * @param Booking $booking
     * @param Carbon|null $recurringDate
     * @return array
     */
    public function getBookingJob(Booking $booking, Carbon $recurringDate = null)
    {
        if (!$recurringDate) {
            if ($booking->isRecurring()) {
                throw new \InvalidArgumentException('Booking is a recurring booking. Individual recurring date is required.');
            }

            return $this->buildJob(
                $booking,
                $this->getProviderDetails($booking),
                [
                    'from' => $booking->getStartDate(),
                    'to' => $booking->getFinalBookingDateTime()
                ]
            );
        }

        $recurringBooking = $this->recurringBookingService->findByEventAndDate($booking->getEvent(), $recurringDate);

        if ($recurringBooking) {
            return $this->buildJob(
                $recurringBooking->getBooking(),
                $this->getProviderDetails($recurringBooking->getBooking()),
                [
                    'from' => $recurringBooking->getBooking()->getStartDate(),
                    'to' => $recurringBooking->getBooking()->getFinalBookingDateTime()
                ]
            );
        }

        if (!$this->bookingEventService->isValidRecurringDate($booking, $recurringDate)) {
            throw new \InvalidArgumentException('Not a valid recurring date received for the booking.');
        }

        return $this->buildJob(
            $booking,
            $this->getProviderDetails($booking),
            [
                'from' => $recurringDate,
                'to' => Booking::calculateFinalBookingDateTime($recurringDate, $booking->getFinalHours())
            ]
        );
    }

    /**
     * @param User $user
     * @param Carbon $from
     * @param Carbon $to
     * @param bool $isProvider
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
        if (!$bookings->count()){
            return $jobs;
        }

        $bookingJobs = [];
        /** @var Booking $booking */
        foreach ($bookings->limit(15)->get('bookings.*') as $booking){
            $providerDetails = $this->getProviderDetails($booking);
            $dates = $this
                ->bookingEventService
                ->listBookingDatesBetween($booking, $from, $to);
            foreach ($dates as $date) {
                $job = $this->buildJob($booking, $providerDetails, $date);
                $bookingJobs[] = $job;
            }
        }

        usort($bookingJobs, function ($a, $b) {
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

        return $bookingJobs;
    }

    /**
     * @param Booking $booking
     * @param array $providerDetails
     * @param array $date
     * @return array
     */
    private function buildJob(Booking $booking, array $providerDetails, array $date): array
    {
        $job = [];
        $job = array_merge($job, $date);
        $job['booking_id'] = $booking->getId();
        $job['is_recurring_item'] = $booking->isRecurring();
        $job['booking_status'] = $booking->getStatus();
        $job['providers'] = $providerDetails;
        $job['booking_service'] = $booking->getBookingServicesArr();;
        $job['booking_status_name'] = Bookingstatus::getStatusNameById($booking->booking_status_id);
        $job['user'] = $booking->getUserDetails();
        $job['address'] = $this->bookingService->getBookingAddress($booking->getId());
        $job['question'] = $this->bookingService->getBookingQuestions($booking->getId());

        return $job;
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

        $acceptedProviders = $this
            ->bookingRequestProviderRepo
            ->getBookingAccptedProvidersDetails($booking->id);

        return [
            'pending' => $pendingProviders,
            'accepted' => $acceptedProviders
        ];
    }
}
