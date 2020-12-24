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

        if ($date->lessThan(Carbon::now())) {
            throw new RecurringBookingCreationException('Recurring booking can not be created for past date');
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
     * @return Collection
     */
    public function findByEvent(Event $event): Collection
    {
        return $this->recurringBookingRepo->findAllByEvent($event);
    }
}