<?php

namespace App\Services\Bookings;

use App\Booking;
use App\BookingNote;
use App\Bookingstatus;
use App\Exceptions\Booking\BookingStatusChangeException;
use App\Exceptions\Booking\InvalidBookingStatusActionException;
use App\Exceptions\Booking\RecurringBookingStatusChangeException;
use App\Exceptions\Booking\UnauthorizedAccessException;
use App\Repository\BookingReqestProviderRepository;
use App\Services\Bookings\Interfaces\BookingStatusChangeStrategyInterface;
use App\Services\RecurringBookingService;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class AbstractBookingStatusChangeStrategy
 * @package App\Services\Bookings
 */
abstract class AbstractBookingStatusChangeStrategy implements BookingStatusChangeStrategyInterface
{
    /**
     * @var BookingReqestProviderRepository
     */
    protected $bookingRequestProviderRepo;

    /**
     * @var BookingVerificationService
     */
    protected $bookingVerificationService;

    /**
     * @var string
     */
    protected $statusChangeMessage;

    /** @var RecurringBookingService */
    protected $recurringBookingService;

    /**
     * @param Booking $booking
     * @param User $user
     * @param Carbon|null $recurredDate
     * @return Booking
     * @throws InvalidBookingStatusActionException
     * @throws UnauthorizedAccessException
     * @throws BookingStatusChangeException
     * @throws RecurringBookingStatusChangeException
     */
    abstract protected function handleStatusChange(
        Booking $booking,
        User $user,
        Carbon $recurredDate = null
    ): Booking;

    /**
     * AbstractBookingStatusChangeStrategy constructor.
     * @param BookingReqestProviderRepository $bookingRequestProviderRepository
     * @param BookingVerificationService $bookingVerificationService
     * @param RecurringBookingService $recurringBookingService
     */
    public function __construct(
        BookingReqestProviderRepository $bookingRequestProviderRepository,
        BookingVerificationService $bookingVerificationService,
        RecurringBookingService $recurringBookingService
    ) {
        $this->bookingRequestProviderRepo = $bookingRequestProviderRepository;
        $this->bookingVerificationService = $bookingVerificationService;
        $this->recurringBookingService = $recurringBookingService;
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
        DB::beginTransaction();
        try {
            if ($recurredDate) {
                $recurringBooking = $this
                    ->recurringBookingService
                    ->findByEventAndDate($booking->getEvent(), $recurredDate);

                if ($recurringBooking) {
                    $booking = $recurringBooking->getBooking();
                }
            }

            $booking = $this->handleStatusChange($booking, $user, $recurredDate);

            if (!$booking) {
                DB::rollBack();
                return null;
            }

            $this->saveBookingNote($booking, $user);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        DB::commit();
        return $booking;
    }

    /**
     * @param Booking $booking
     * @param User $user
     * @return bool
     */
    public function canUserUpdateBooking(Booking $booking, User $user): bool
    {
        return $this->bookingVerificationService->canUserUpdateBooking($booking, $user);
    }

    /**
     * @param User $user
     * @param Booking $booking
     * @return bool
     */
    protected function isUserTheBookingCustomer(User $user, Booking $booking): bool
    {
        return $this->bookingVerificationService->isUserTheBookingCustomer($user, $booking);
    }

    /**
     * @param Booking $booking
     * @param User $user
     */
    protected function saveBookingNote(Booking $booking, User $user)
    {
        if ($this->getStatusChangeMessage()) {
            $bookingNote = new BookingNote();
            $bookingNote->setBookingStatusId($booking->getStatus())
                ->setUserId($user->getId())
                ->setNotes($this->getStatusChangeMessage());

            $booking->saveBookingNotes([$bookingNote]);
        }
    }

    /**
     * @param User $user
     * @param Booking $booking
     * @return bool
     */
    protected function isUserAChosenBookingProvider(User $user, Booking $booking): bool
    {
        return $this->bookingVerificationService->isUserAChosenBookingProvider($user, $booking);
    }

    /**
     * @param string $message
     * @return AbstractBookingStatusChangeStrategy
     */
    public function setStatusChangeMessage(string $message)
    {
        $this->statusChangeMessage = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatusChangeMessage(): string
    {
        return (($this->statusChangeMessage!=null) ? $this->statusChangeMessage : '');
    }
}