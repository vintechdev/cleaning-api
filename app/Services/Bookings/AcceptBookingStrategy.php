<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Bookingrequestprovider;
use App\Bookingstatus;
use App\Events\BookingStatusChanged;
use App\Exceptions\Booking\BookingStatusChangeException;
use App\Exceptions\Booking\InvalidBookingStatusActionException;
use App\Exceptions\Booking\UnauthorizedAccessException;
use App\User;

/**
 * Class AcceptBookingStrategy
 * @package App\Services\Bookings
 */
class AcceptBookingStrategy extends AbstractBookingStatusChangeStrategy
{
    /**
     * @param Booking $booking
     * @param User $user
     * @return bool
     * @throws InvalidBookingStatusActionException
     * @throws UnauthorizedAccessException
     * @throws BookingStatusChangeException
     */
    public function changeStatus(Booking $booking, User $user): bool
    {
        if (!$this->canUserAcceptBooking($booking, $user)) {
            throw new UnauthorizedAccessException('User does not have permission to accept booking');
        }

        $request = $this->bookingRequestProviderRepo->getByBookingAndProviderId($booking->getId(), $user->getId());

        if (
            !$booking->setStatus(Bookingstatus::BOOKING_STATUS_ACCEPTED)->save() ||
            !$request->setStatus(Bookingrequestprovider::STATUS_ACCEPTED)->save()
        ) {
            throw new BookingStatusChangeException('Acceppt booking failed while saving Booking request');
        }
        return true;
    }

    /**
     * TODO: Admin can accept booking for a provider. Add another function that call this.
     * @param Booking $booking
     * @param User $user
     * @return bool
     * @throws InvalidBookingStatusActionException
     */
    public function canUserAcceptBooking(Booking $booking, User $user): bool
    {
        if ($booking->getStatus() != Bookingstatus::BOOKING_STATUS_PENDING) {
            throw new InvalidBookingStatusActionException('This booking is not in pending state');
        }

        if (!$this->canUserUpdateBooking($booking, $user)) {
            return false;
        }

        if ($this->isUserAChosenBookingProvider($user, $booking)) {
            $requestProvider = $this->bookingRequestProviderRepo->getByBookingAndProviderId($booking->getId(), $user->getId());
            if ($requestProvider->getStatus() == Bookingrequestprovider::STATUS_PENDING) {
                return true;
            }
        }

        return false;
    }
}