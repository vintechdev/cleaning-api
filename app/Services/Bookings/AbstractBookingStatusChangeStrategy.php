<?php

namespace App\Services\Bookings;

use App\Booking;
use App\BookingNote;
use App\Exceptions\Booking\BookingStatusChangeException;
use App\Exceptions\Booking\InvalidBookingStatusActionException;
use App\Exceptions\Booking\RecurringBookingStatusChangeException;
use App\Exceptions\Booking\UnauthorizedAccessException;
use App\Repository\BookingReqestProviderRepository;
use App\Services\Bookings\Interfaces\BookingStatusChangeStrategyInterface;
use App\User;
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

    /**
     * @param Booking $booking
     * @param User $user
     * @return bool
     * @throws InvalidBookingStatusActionException
     * @throws UnauthorizedAccessException
     * @throws BookingStatusChangeException
     */
    abstract protected function handleStatusChange(Booking $booking, User $user): bool;

    /**
     * AbstractBookingStatusChangeStrategy constructor.
     * @param BookingReqestProviderRepository $bookingReqestProviderRepository
     * @param BookingVerificationService $bookingVerificationService
     */
    public function __construct(
        BookingReqestProviderRepository $bookingReqestProviderRepository,
        BookingVerificationService $bookingVerificationService
    ) {
        $this->bookingRequestProviderRepo = $bookingReqestProviderRepository;
        $this->bookingVerificationService = $bookingVerificationService;
    }

    /**
     * @param Booking $booking
     * @param User $user
     * @return bool
     * @throws InvalidBookingStatusActionException
     * @throws UnauthorizedAccessException
     * @throws BookingStatusChangeException
     * @throws RecurringBookingStatusChangeException
     */
    public function changeStatus(Booking $booking, User $user): bool
    {
        if ($booking->isRecurring()) {
            throw new RecurringBookingStatusChangeException(
                'Booking status for recurring booking can not be changed. Individual recurred bookings need to be updated.'
            );
        }

        DB::beginTransaction();
        try {
            if (!$this->handleStatusChange($booking, $user)) {
                DB::rollBack();
                return false;
            }

            $this->saveBookingNote($booking, $user);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        DB::commit();
        return true;
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
        return $this->statusChangeMessage;
    }
}