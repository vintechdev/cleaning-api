<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Exceptions\Booking\UnauthorizedAccessException;
use App\Repository\BookingReqestProviderRepository;
use App\Repository\UserBadgeReviewRepository;
use App\Services\BookingEventService;
use App\Services\RecurringBookingService;
use App\User;
use Carbon\Carbon;
use App\Bookingstatus;
use Illuminate\Database\Eloquent\Collection;
use App\Userreview;

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
     * @var UserBadgeReviewRepository
     */
    private $badgeReviewRepo;

    /**
     * @var BookingListFactory
     */
    private $bookingListFactory;

    /**
     * @var BookingVerificationService
     */
    private $bookingVerificationService;

    /**
     * BookingManager constructor.
     * @param BookingListFactory $bookingListFactory
     * @param BookingEventService $bookingEventService
     * @param BookingReqestProviderRepository $bookingReqestProviderRepository
     * @param RecurringBookingService $recurringBookingService
     * @param BookingService $bookingService
     * @param UserBadgeReviewRepository $badgeReviewRepository
     * @param BookingVerificationService $bookingVerificationService
     */
    public function __construct(
        BookingListFactory $bookingListFactory,
        BookingEventService $bookingEventService,
        BookingReqestProviderRepository $bookingReqestProviderRepository,
        RecurringBookingService $recurringBookingService,
        BookingService $bookingService,
        UserBadgeReviewRepository $badgeReviewRepository,
        BookingVerificationService $bookingVerificationService
    ) {
        $this->bookingListFactory = $bookingListFactory;
        $this->bookingEventService = $bookingEventService;
        $this->bookingRequestProviderRepo = $bookingReqestProviderRepository;
        $this->recurringBookingService = $recurringBookingService;
        $this->bookingService = $bookingService;
        $this->badgeReviewRepo = $badgeReviewRepository;
        $this->bookingVerificationService = $bookingVerificationService;
    }

    /**
     * @param int $statusId
     * @param User $user
     * @param bool $isProvider
     * @param Carbon|null $from
     * @param Carbon|null $to
     * @return array
     */
    public function getBookingJobsByStatus(
        int $statusId,
        User $user,
        bool $isProvider = false,
        Carbon $from= null,
        Carbon $to = null
    ): array
    {
        $bookingList = $this
            ->bookingListFactory
            ->create($statusId);
        $bookings = $bookingList
            ->setUser($user)
            ->setIsProvider($isProvider)
            ->setFrom($from)
            ->setTo($to)
            ->getAllBookings();

        if (!$bookings->count()) {
            return [];
        }

        if ($statusId != Bookingstatus::BOOKING_STATUS_ACCEPTED) {
            $bookingJobs = [];
            /** @var Booking $booking */
            foreach ($bookings as $booking) {
                $providerDetails = $this->getProviderDetails($booking, true);
                $services = $this->buildBookingServices($booking);
                $bookingJobs[] = $this
                    ->buildJob(
                        $booking,
                        $providerDetails,
                        ['from' => $booking->getStartDate(), 'to' => $booking->getFinalBookingDateTime()],
                        $services,
                        true,
                        true
                    );
            }

            return $bookingJobs;
        }

        // This will have recurring bookings with logical entries to DB as separate booking entity.
        return $this->buildAcceptedBookingJobs($bookings, $bookingList->getFrom(), $bookingList->getTo());
    }

    /**
     * @param Booking $booking
     * @param Carbon|null $recurringDate
     * @return array
     */
    public function getBookingJob(Booking $booking, Carbon $recurringDate = null)
    {
        if (!$recurringDate) {
            return $this->buildJob(
                $booking,
                $this->getProviderDetails($booking),
                [
                    'from' => $booking->getStartDate(),
                    'to' => $booking->getFinalBookingDateTime()
                ],
                $this->buildBookingServices($booking)
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
                ],
                $this->buildBookingServices($recurringBooking->getBooking())
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
                'to' => Booking::calculateFinalBookingDateTime($recurringDate, $booking->getTotalHours())
            ],
            $this->buildBookingServices($booking)
        );
    }

    /**
     * @param Booking $booking
     * @param Carbon $newDatetime
     * @param User $user
     * @param Carbon|null $recurringDate
     * @return array
     * @throws \App\Exceptions\NoSavedCardException
     */
    public function changeJobDateTime(Booking $booking, Carbon $newDatetime, User $user, Carbon $recurringDate = null): array
    {
        if ($newDatetime->lessThan(Carbon::now())) {
            throw new \InvalidArgumentException('Can not change to a data lesser than today\'s');
        }

        $recurringBooking = null;
        if ($booking->isRecurring()) {
            if (!$recurringDate) {
                throw new \InvalidArgumentException('A valid recurring date needs to be passed with recurring booking id.');
            }

            $recurringBooking = $this->recurringBookingService->findOrCreateRecurringBooking($booking, $recurringDate);
            $booking = $recurringBooking->getBooking();
        } elseif ($booking->isChildBooking()) {
            $recurringBooking = $this->recurringBookingService->findByChildBooking($booking);
        }

        if ($booking->getStatus() != Bookingstatus::BOOKING_STATUS_ACCEPTED) {
            throw new UnauthorizedAccessException('Bookings can only be rescheduled when it is in accepted state.');
        }

        if (!$user->isAdmin() && !$this->bookingVerificationService->hasUserAcceptedBooking($user, $booking)) {
            throw new UnauthorizedAccessException('User does not have access to reschedule booking date time.');
        }

        if ($booking->setStartDateTime($newDatetime)->save()) {
            if ($recurringBooking) {
                $recurringBooking->setRescheduled()->save();
            }
            $providerDetails = $this->getProviderDetails($booking, true);
            $services = $this->buildBookingServices($booking);
            return $this
                ->buildJob(
                    $booking,
                    $providerDetails,
                    ['from' => $booking->getStartDate(), 'to' => $booking->getFinalBookingDateTime()],
                    $services,
                    true
                );
        }

        throw new \Exception('Something went wrong when saving booking after changing it to a new date time');
    }

    /**
     * @param Booking $booking
     * @return array
     */
    private function buildBookingServices(Booking $booking) : array
    {
        $services = [];
        /** @var \App\Bookingservice $service */
        foreach ($booking->getBookingServices() as $service) {
            $serviceArray = $service->toArray();
            $serviceArray['name'] = $service->getService()->getName();
            $serviceArray['category'] = $service->getService()->getServicecategory();
            $serviceArray['unit_type'] = $service->getService()->unit_type;
            $serviceArray['max_hours'] = $service->getService()->getMaxHours();
            $serviceArray['min_hours'] = $service->getService()->getMinHours();
            $services[] = $serviceArray;
        }

        return $services;
    }

    /**
     * @param Booking $booking
     * @param array $providerDetails
     * @param array $date
     * @param array $services
     * @param bool $addDetailsUrl
     * @param bool $isList
     * @return array
     */
    private function buildJob(
        Booking $booking,
        array $providerDetails,
        array $date,
        array $services,
        bool $addDetailsUrl = false,
        bool $isList = false
    ): array
    {
        $job = [];

        if ($addDetailsUrl) {
            if ($booking->isRecurring() && isset($date['to']) && !is_null($date['to'])) {
                $job['details_url'] = route('getrecurredbookingdetails', [$booking->getId(), $date['from']->format('dmYHis')]);
            } else {
                $job['details_url' ] = route('getbookingdetails', [$booking->getId()]);
            }
        }

        $job = array_merge($job, $date);
        $job['final_hours'] = $booking->getFinalHours();
        $job['is_recurring'] = $booking->isRecurring() || $booking->isChildBooking();
        $job['booking_id'] = $booking->getId();
        $job['plan_name'] = $booking->isChildBooking() ? $booking->getParentBooking()->getPlan()->plan_name : $booking->getPlan()->plan_name;
        $job['booking_provider_type'] = $booking->booking_provider_type;
        $job['promo_code'] = $booking->promocode;
        $job['total_cost'] = $booking->total_cost;
        $job['discount'] = $booking->discount;
        $job['plan_discount'] = $booking->plan_discount;
        $job['final_cost'] = $booking->final_cost;
        $job['booking_status'] = $booking->getStatus();
        $job['providers'] = $providerDetails;
        $job['booking_service'] = $services;
        $job['is_flexible'] = $booking->getIsFlexible();
        $job['booking_review'] = $this->badgeReviewRepo->getreviewbybooking($booking->getId());
        $job['booking_status_name'] = Bookingstatus::getStatusNameById($booking->booking_status_id);
        $job['user'] = $booking->getUserDetails();
        if (!$isList) {
            $job['address'] = $this->bookingService->getBookingAddress($booking->getId());
            $job['question'] = $this->bookingService->getBookingQuestions($booking->getId());
        }

        $job['booking_postcode'] = $booking->booking_postcode;
        $job['created_at'] = $booking->created_at;

        return $job;
    }

    private function buildAcceptedBookingJobs(Collection $bookings, Carbon $from, Carbon $to): array
    {
        $bookingJobs = [];
        /** @var Booking $booking */
        foreach ($bookings as $booking) {
            $providerDetails = $this->getProviderDetails($booking, true);
            $services = $this->buildBookingServices($booking);
            // These dates will ignore recurring bookings that belongs to the parent which has separate logical db
            // entry which would have been created as a result of rescheduling. This is because the list of bookings
            // that we received will have those recurring bookings as separate bookings.
            $dates = $this
                ->bookingEventService
                ->listBookingDatesBetween($booking, $from, $to);
            foreach ($dates as $date) {
                $job = $this->buildJob($booking, $providerDetails, $date, $services, true, true);
                $bookingJobs[] = $job;
            }
        }

        usort($bookingJobs, function ($a, $b) {
            if ($b['from']->lessThan($a['from'])) {
                return true;
            }

            if ($b['from']->equalTo($a['from'])) {
                return ($b['booking_id'] <= $a['booking_id']);
            }

            return false;
        });

        return $bookingJobs;
    }

    /**
     * @param Booking $booking
     * @param bool $isList
     * @return array
     */
    private function getProviderDetails(Booking $booking, bool $isList = false): array
    {
        $providers = $this->bookingRequestProviderRepo->getBookingProvidersData($booking->getId());

        if (!$isList) {
            foreach ($providers as &$provider) {
                $provider['badges'] = $this->badgeReviewRepo->getBadgeDetails($provider['provider_user_id']);
                $provider['review'] = $this->badgeReviewRepo->getReviewDetails($provider['provider_user_id']);
                $provider['avgrate'] = $this->badgeReviewRepo->getAvgRating($provider['provider_user_id']);
            }
        }

        return $providers;
    }
}
