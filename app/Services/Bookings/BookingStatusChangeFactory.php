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
            case 'rejected':
                return app(RejectBookingStrategy::class);
            case 'accepted':
                return app(AcceptBookingStrategy::class);
            case 'cancelled':
                return app(CancelBookingStrategy::class);
            case 'arrived':
                return app(ArriveBookingStrategy::class);
            case 'completed':
                return app(CompleteBookingStrategy::class);
            default:
                throw new InvalidBookingStatusException('Invalid booking status received');
        }
    }
}