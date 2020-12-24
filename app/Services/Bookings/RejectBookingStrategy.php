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
 * Class RejectBookingStrategy
 * @package App\Services\Bookings
 */
class RejectBookingStrategy extends AbstractBookingStatusChangeStrategy
{
    /**
     * @param Booking $booking
     * @param User $user
     * @return bool
     * @throws InvalidBookingStatusActionException
     * @throws UnauthorizedAccessException
     * @throws BookingStatusChangeException
     */
    protected function handleStatusChange(Booking $booking, User $user): bool
    {
        if (!$this->canUserRejectBooking($booking, $user)) {
            throw new UnauthorizedAccessException('User does not have permission to reject this booking');
        }

        $requestProvider = $this
            ->bookingRequestProviderRepo
            ->getByBookingAndProviderId($booking->getId(), $user->getId());

        $requestProvider->setStatus(Bookingrequestprovider::STATUS_REJECTED)->save();

        if (
            !$this
                ->bookingRequestProviderRepo
                ->getCountWithStatuses(
                    [Bookingrequestprovider::STATUS_PENDING, Bookingrequestprovider::STATUS_ACCEPTED], $booking->getId()
                ) &&
            !$booking->setStatus(Bookingstatus::BOOKING_STATUS_REJECTED)->save()
        ) {
            throw new BookingStatusChangeException('Booking cancellation failed while saving Booking request');
        }

        return true;
    }

    /**
     * @param Booking $booking
     * @param User $user
     * @return bool
     */
    public function canUserRejectBooking(Booking $booking, User $user): bool
    {
        if (!$this->canUserUpdateBooking($booking, $user)) {
            return false;
        }

        if ($this->isUserTheBookingCustomer($user, $booking)) {
            return false;
        }

        if (
            $this->isUserAChosenBookingProvider($user, $booking) &&
            $booking->getStatus() == Bookingstatus::BOOKING_STATUS_PENDING
        ) {
            return true;
        }

        return false;
    }
}