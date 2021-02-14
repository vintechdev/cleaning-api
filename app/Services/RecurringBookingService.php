<?php

namespace App\Services;

use App\Booking;
use App\Event;
use App\Exceptions\NoSavedCardException;
use App\Exceptions\RecurringBookingCreationException;
use App\RecurringBooking;
use App\Repository\Eloquent\RecurringBookingRepository;
use App\Services\Bookings\BookingService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class RecurringBookingService
 * @package App\Services
 */
class RecurringBookingService
{
    /**
     * @var RecurringBookingRepository
     */
    private $recurringBookingRepo;

    /**
     * @var RecurringPatternService
     */
    private $recurringPatternService;

    /**
     * @var BookingService
     */
    private $bookingService;

    /**
     * RecurringBookingService constructor.
     * @param RecurringBookingRepository $recurringBookingRepository
     * @param RecurringPatternService $recurringPatternService
     * @param BookingService $bookingService
     */
    public function __construct(
        RecurringBookingRepository $recurringBookingRepository,
        RecurringPatternService $recurringPatternService,
        BookingService $bookingService
    ) {
        $this->recurringBookingRepo = $recurringBookingRepository;
        $this->recurringPatternService = $recurringPatternService;
        $this->bookingService = $bookingService;
    }

    /**
     * @param Booking $booking
     * @param Carbon $date
     * @return RecurringBooking
     * @throws NoSavedCardException
     * @throws RecurringBookingCreationException
     * @throws \InvalidArgumentException
     */
    public function findOrCreateRecurringBooking(Booking $booking, Carbon $date): RecurringBooking
    {
        if ($booking->isChildBooking()) {
            throw new \InvalidArgumentException('The booking passed is already a recurred booking');
        }

        if (is_null($booking->getEvent())) {
            throw new \InvalidArgumentException('The booking passed is not a recurring booking');
        }

        if (!$this->recurringPatternService->isValidRecurringDate($booking->getEvent(), $date)) {
            throw new \InvalidArgumentException('Date passed is not a valid recurring date');
        }

        $recurringBooking = $this->recurringBookingRepo->findByEventAndDate($booking->getEvent(), $date);

        if ($recurringBooking) {
            return $recurringBooking;
        }

        $childBooking = $this->bookingService->createChildBooking($booking);

        /** @var RecurringBooking $recurringBooking */
        $recurringBooking = $this->recurringBookingRepo->create([
            'event_id' => $booking->getEvent()->getId(),
            'recurred_timestamp' => $date->format('Y-m-d H:i:s'),
            'booking_id' => $childBooking->getId()
        ]);

        $recurringBooking->getBooking()->setStartDateTime($date)->save();
        return $recurringBooking;
    }

    /**
     * @param Booking $booking
     * @param Carbon $date
     * @return bool
     */
    public function cancelAllBookingsAfter(Booking $booking, Carbon $date): bool
    {
        if (!$booking->isRecurring()) {
            throw new \InvalidArgumentException('Cancelling recurring bookings require the parent booking id to carry out the operation.');
        }

        return $this->recurringPatternService->cancelEventAfter($booking->getEvent(), $date);
    }

    /**
     * @param Booking $booking
     * @param Carbon $date
     * @return bool
     */
    public function isValidRecurringDate(Booking $booking, Carbon $date): bool
    {
        if (!$booking->isRecurring()) {
            return false;
        }

        return $this->recurringPatternService->isValidRecurringDate($booking->getEvent(), $date);
    }

    /**
     * @param Event $event
     * @param array $dates
     * @return Collection
     */
    public function findByEventAndDates(Event $event, array $dates): Collection
    {
        return $this->recurringBookingRepo->findAllByEventAndDates($event, $dates);
    }

    /**
     * @param Event $event
     * @param Carbon $date
     * @return RecurringBooking|null
     */
    public function findByEventAndDate(Event $event, Carbon $date): ?RecurringBooking
    {
        return $this->recurringBookingRepo->findByEventAndDate($event, $date);
    }

    /**
     * @param Event $event
     * @return Collection
     */
    public function findByEvent(Event $event): Collection
    {
        return $this->recurringBookingRepo->findAllByEvent($event);
    }

    /**
     * @param Event $event
     * @return Collection
     */
    public function findAllRescheduledByEvent(Event $event): Collection
    {
        return $this->recurringBookingRepo->findAllRescheduledByEvent($event);
    }

    /**
     * @param Booking $booking
     * @return RecurringBooking|null
     */
    public function findByChildBooking(Booking $booking): ?RecurringBooking
    {
        if (!$booking->isChildBooking()) {
            throw new \InvalidArgumentException('Booking is not a child booking.');
        }
        $this->recurringBookingRepo->findByChildBooking($booking);
    }
}