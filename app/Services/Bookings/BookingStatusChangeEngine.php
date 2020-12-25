<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Bookingstatus;
use App\Exceptions\Booking\InvalidBookingStatusActionException;
use App\Exceptions\Booking\InvalidBookingStatusException;
use App\Exceptions\Booking\RecurringBookingStatusChangeException;
use App\Exceptions\Booking\UnauthorizedAccessException;
use App\Exceptions\NoSavedCardException;
use App\Exceptions\RecurringBookingCreationException;
use App\Plan;
use App\Services\Bookings\Builder\BookingStatusChangeContextBuilder;
use App\Services\RecurringBookingService;
use App\User;
use Carbon\Carbon;

/**
 * Class BookingStatusChangeEngine
 * @package App\Services\Bookings
 */
class BookingStatusChangeEngine
{
    /**
     * @var BookingStatusChangeContextBuilder
     */
    private $statusChangeContextBuilder;

    /**
     * @var Booking
     */
    private $booking;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Carbon
     */
    private $recurredDate;

    /**
     * @var array
     */
    private $statusChangeParameters;

    /**
     * @var RecurringBookingService
     */
    private $recurringBookingService;

    /**
     * BookingStatusChangeEngine constructor.
     * @param BookingStatusChangeContextBuilder $statusChangeContextBuilder
     * @param RecurringBookingService $recurringBookingService
     */
    public function __construct(
        BookingStatusChangeContextBuilder $statusChangeContextBuilder,
        RecurringBookingService $recurringBookingService
    ) {
        $this->statusChangeContextBuilder = $statusChangeContextBuilder;
        $this->recurringBookingService = $recurringBookingService;
    }

    /**
     * @param Booking $booking
     * @return $this
     */
    public function setBooking(Booking $booking): BookingStatusChangeEngine
    {
        $this->booking = $booking;
        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user): BookingStatusChangeEngine
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param Carbon|null $date
     * @return $this
     */
    public function setRecurredDate(Carbon $date = null): BookingStatusChangeEngine
    {
        $this->recurredDate = $date;
        return $this;
    }

    /**
     * @param array $statusChangeParameters
     * @return BookingStatusChangeEngine
     */
    public function setStatusChangeParameters(array $statusChangeParameters): BookingStatusChangeEngine
    {
        $this->statusChangeParameters = $statusChangeParameters;
        return $this;
    }

    /**
     * @param string $status
     * @return Booking
     * @throws RecurringBookingStatusChangeException
     * @throws UnauthorizedAccessException
     * @throws NoSavedCardException
     * @throws InvalidBookingStatusException
     * @throws InvalidBookingStatusActionException
     * @throws RecurringBookingCreationException
     * @throws \InvalidArgumentException
     */
    public function changeStatus(string $status)
    {
        if (!$this->booking->isRecurring() && !$this->booking->isRecurredBooking()) {
            $this->runStatusChange($status);
            return $this->booking;
        }

        if (
            ($this->booking->isRecurredBooking() || ($this->booking->isRecurring() && $this->recurredDate)) &&
            in_array($status, [BookingStatusChangeTypes::STATUS_ACCEPTED, BookingStatusChangeTypes::STATUS_REJECTED])
        ) {
            throw new RecurringBookingStatusChangeException('A recurred booking item cannot be accepted or rejected.');
        }

        if (
            $this->booking->isRecurring() &&
            $this->recurredDate &&
            !in_array($this->booking->getStatus(), [Bookingstatus::BOOKING_STATUS_ACCEPTED])
        ) {
            throw new RecurringBookingStatusChangeException('The booking needs to be accepted before changing the status to anything else');
        }

        if ($this->recurredDate) {
            $recurringBooking = $this
                ->recurringBookingService
                ->findOrCreateRecurringBooking($this->booking, $this->recurredDate);

            return $this
                ->setBooking($recurringBooking->getBooking())
                ->setRecurredDate(null)
                ->changeStatus($status);
        }

        $this->runStatusChange($status);
        return $this->booking;
    }

    /**
     * @param $status
     * @return bool
     * @throws RecurringBookingStatusChangeException
     * @throws UnauthorizedAccessException
     * @throws NoSavedCardException
     * @throws InvalidBookingStatusException
     * @throws InvalidBookingStatusActionException
     * @throws RecurringBookingCreationException
     * @throws \InvalidArgumentException
     */
    private function runStatusChange($status): bool
    {
        $context = $this->statusChangeContextBuilder->buildContext($status, $this->statusChangeParameters);
        return $context->changeStatus($this->booking, $this->user);
    }
}
