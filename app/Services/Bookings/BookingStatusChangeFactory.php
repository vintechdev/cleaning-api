<?php

namespace App\Services\Bookings;

use App\Exceptions\Booking\InvalidBookingStatusException;
use App\Services\Bookings\Interfaces\BookingStatusChangeStrategyInterface;

/**
 * Class BookingStatusChangeFactory
 * @package App\Services\Bookings
 */
class BookingStatusChangeFactory
{
    /**
     * @param string $status
     * @param array $parameters
     * @return BookingStatusChangeStrategyInterface
     * @throws InvalidBookingStatusException
     */
    public function create(string $status, array $parameters): BookingStatusChangeStrategyInterface
    {
        $strategy = $this->getStrategy($status);

        if (isset($parameters['status_change_message'])) {
            $strategy->setStatusChangeMessage($parameters['status_change_message']);
        }

        if ($strategy instanceof CompleteBookingStrategy && isset($parameters['services'])) {
            $strategy->setServicesDetails($parameters['services']);
        }

        return $strategy;
    }

    /**
     * @param string $status
     * @return BookingStatusChangeStrategyInterface
     * @throws InvalidBookingStatusException
     */
    private function getStrategy(string $status): BookingStatusChangeStrategyInterface
    {
        switch ($status) {
            case BookingStatusChangeTypes::STATUS_REJECTED:
                return app(RejectBookingStrategy::class);
            case BookingStatusChangeTypes::STATUS_ACCEPTED:
                return app(AcceptBookingStrategy::class);
            case BookingStatusChangeTypes::STATUS_CANCELLED:
                return app(CancelBookingStrategy::class);
            case BookingStatusChangeTypes::STATUS_ARRIVED:
                return app(ArriveBookingStrategy::class);
            case BookingStatusChangeTypes::STATUS_COMPLETED:
                return app(CompleteBookingStrategy::class);
            case BookingStatusChangeTypes::STATUS_CANCEL_AFTER:
                return app(CancelAfterBookingStrategy::class);
            case BookingStatusChangeTypes::STATUS_APPROVED:
                return app(ApproveBookingStrategy::class);
            default:
                throw new InvalidBookingStatusException('Invalid booking status received');
        }
    }
}