<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Bookingrequestprovider;
use App\Bookingstatus;
use App\Events\BookingStatusChanged;
use App\Exceptions\Booking\BookingManagerException;
use App\Exceptions\Booking\InvalidBookingStatusActionException;
use App\Exceptions\Booking\UnauthorizedAccessException;
use App\Plan;
use App\Repository\BookingReqestProviderRepository;
use App\User;

/**
 * Class BookingStatusManager
 * @package App\Services\Bookings
 */
class BookingStatusManager
{
    /**
     * @var BookingReqestProviderRepository
     */
    private $bookingRequestProviderRepo;

    /**
     * BookingManager constructor.
     * @param BookingReqestProviderRepository $bookingReqestProviderRepository
     */
    public function __construct(BookingReqestProviderRepository $bookingReqestProviderRepository)
    {
        $this->bookingRequestProviderRepo = $bookingReqestProviderRepository;
    }

    /**
     * @param Booking $booking
     * @param User $user
     * @throws BookingManagerException
     * @throws UnauthorizedAccessException
     */
    public function cancelBooking(Booking $booking, User $user)
    {
        if (!$this->canUserCancelBooking($booking, $user)) {
            throw new UnauthorizedAccessException('User does not have permission to cancel this booking');
        }

        $oldStatus = $booking->getStatus();

        if (!$booking->setStatus(Bookingstatus::BOOKING_STATUS_CANCELLED)->save()) {
            throw new BookingManagerException('Booking cancellation failed while saving');
        }

        $providers = $this
            ->bookingRequestProviderRepo
            ->getAllWithStatuses([Bookingrequestprovider::STATUS_ACCEPTED], $booking->getId());

        /** @var Bookingrequestprovider $provider */
        foreach ($providers as $provider) {
            if (!$provider->setStatus(Bookingrequestprovider::STATUS_CANCELLED)->save()) {
                throw new BookingManagerException('Booking cancellation failed while saving Booking requests');
            }
        }

        event(new BookingStatusChanged($booking, $user, $oldStatus, $booking->getStatus()));
        return true;
    }

    /**
     * // TODO: Admin can reject for a provider. Create another function that call this function
     * @param Booking $booking
     * @param User $user
     * @return bool
     * @throws UnauthorizedAccessException
     * @throws BookingManagerException
     */
    public function rejectBooking(Booking $booking, User $user): bool
    {
        if (!$this->canUserRejectBooking($booking, $user)) {
            throw new UnauthorizedAccessException('User does not have permission to reject this booking');
        }

        $requestProvider = $this
            ->bookingRequestProviderRepo
            ->getByBookingAndProviderId($booking->getId(), $user->getId());

        $oldStatus = $booking->getStatus();
        $requestProvider->setStatus(Bookingrequestprovider::STATUS_REJECTED)->save();

        if (
            !$this
                ->bookingRequestProviderRepo
                ->getCountWithStatuses(
                    [Bookingrequestprovider::STATUS_PENDING, Bookingrequestprovider::STATUS_ACCEPTED], $booking->getId()
                ) &&
            !$booking->setStatus(Bookingstatus::BOOKING_STATUS_REJECTED)->save()
        ) {
            throw new BookingManagerException('Booking cancellation failed while saving Booking request');
        }

        event(new BookingStatusChanged($booking, $user, $oldStatus, $booking->getStatus()));

        return true;
    }

    /**
     * // TODO: Admin can accept for a provider. Create another function that call this function
     * @param Booking $booking
     * @param User $user
     * @return bool
     * @throws UnauthorizedAccessException
     * @throws BookingManagerException
     */
    public function acceptBooking(Booking $booking, User $user): bool
    {
        if (!$this->canUserAcceptBooking($booking, $user)) {
            throw new UnauthorizedAccessException('User does not have permission to accept booking');
        }

        $request = $this->bookingRequestProviderRepo->getByBookingAndProviderId($booking->getId(), $user->getId());

        $oldStatus = $booking->getStatus();
        if (
            !$booking->setStatus(Bookingstatus::BOOKING_STATUS_ACCEPTED)->save() ||
            !$request->setStatus(Bookingrequestprovider::STATUS_ACCEPTED)->save()
        ) {
            throw new BookingManagerException('Acceppt booking failed while saving Booking request');
        }

        event(new BookingStatusChanged($booking, $user, $oldStatus, $booking->getStatus()));
        return true;
    }

    /**
     * @param Booking $booking
     * @param User $user
     * @return bool
     * @throws UnauthorizedAccessException
     * @throws BookingManagerException
     */
    public function arriveForBooking(Booking $booking, User $user): bool
    {
        if (!$this->canUserArriveForBooking($booking, $user)) {
            throw new UnauthorizedAccessException('User does not have access to this function');
        }

        $oldStatus = $booking->getStatus();

        if (!$booking->setStatus(Bookingstatus::BOOKING_STATUS_ARRIVED)->save()) {
            throw new BookingManagerException('Failed to change the status');
        }

        event(new BookingStatusChanged($booking, $user, $oldStatus, $booking->getStatus()));

        return true;
    }

    /**
     * @param Booking $booking
     * @param User $user
     * @return bool
     * @throws UnauthorizedAccessException
     */
    public function completeBooking(Booking $booking, User $user): bool
    {
        if (!$this->canUserCompleteBooking($booking, $user)) {
            throw new UnauthorizedAccessException('User does not have access to this function');
        }

        $oldStatus = $booking->getStatus();

        if (!$booking->setStatus(Bookingstatus::BOOKING_STATUS_COMPLETED)->save()) {
            throw new BookingManagerException('Unable to save booking status');
        }

        event(new BookingStatusChanged($booking, $user, $oldStatus, $booking->getStatus()));

        return true;
    }

    /**
     * @param Booking $booking
     * @param User $user
     * @return bool
     * @throws InvalidBookingStatusActionException
     */
    public function canUserCompleteBooking(Booking $booking, User $user): bool
    {
        if ($booking->getStatus() != Bookingstatus::BOOKING_STATUS_ARRIVED) {
            throw new InvalidBookingStatusActionException('Can not change the status of this booking to complete');
        }

        if (!$this->canUserUpdateBooking($booking, $user)) {
            return false;
        }

        if ($this->isUserAChosenBookingProvider($user, $booking)) {
            $bookingRequestProvider = $this
                ->bookingRequestProviderRepo
                ->getByBookingAndProviderId($booking->getId(), $user->getId());
            if ($bookingRequestProvider->getStatus() == Bookingrequestprovider::STATUS_ACCEPTED) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Booking $booking
     * @param User $user
     * @return bool
     * @throws InvalidBookingStatusActionException
     */
    public function canUserArriveForBooking(Booking $booking, User $user): bool
    {
        if ($booking->getStatus() != Bookingstatus::BOOKING_STATUS_ACCEPTED) {
            throw new InvalidBookingStatusActionException('Can not change the status of this booking to arrived');
        }

        if (!$this->canUserUpdateBooking($booking, $user)) {
            return false;
        }

        if ($this->isUserAChosenBookingProvider($user, $booking)) {
            $bookingRequestProvider = $this
                ->bookingRequestProviderRepo
                ->getByBookingAndProviderId($booking->getId(), $user->getId());
            if ($bookingRequestProvider->getStatus() == Bookingrequestprovider::STATUS_ACCEPTED) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Booking $booking
     * @param User $user
     * @return bool
     */
    public function canUserUpdateBooking(Booking $booking, User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($this->isUserTheBookingCustomer($user, $booking)) {
            return true;
        }

        if ($this->isUserAChosenBookingProvider($user, $booking)) {
            return true;
        }

        return false;
    }

    /**
     * @param Booking $booking
     * @param User $user
     * @return bool
     */
    public function canUserCancelBooking(Booking $booking, User $user): bool
    {
        if (!$this->canUserUpdateBooking($booking, $user)) {
            return false;
        }

        // If admin and status is pending or accepted
        if (
            $user->isAdmin() &&
            in_array(
                $booking->getStatus(),
                [Bookingstatus::BOOKING_STATUS_PENDING, Bookingstatus::BOOKING_STATUS_ACCEPTED]
            )
        ) {
            return true;
        }

        if (
            $this->isUserAChosenBookingProvider($user, $booking) &&
            $booking->getStatus() == Bookingstatus::BOOKING_STATUS_ACCEPTED
        ) {
            $request = $this->bookingRequestProviderRepo->getByBookingAndProviderId($booking->getId(), $user->getId());
            if ($request->getStatus() == Bookingrequestprovider::STATUS_ACCEPTED) {
                return true;
            }
        }

        // If customer and status is pending
        if (
            $this->isUserTheBookingCustomer($user, $booking) &&
            $booking->getStatus() == Bookingstatus::BOOKING_STATUS_PENDING
        ) {
            return true;
        }

        return false;
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
            $booking->getStatus() ==
            Bookingstatus::BOOKING_STATUS_PENDING
        ) {
            return true;
        }

        return false;
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

    /**
     * @param User $user
     * @param Booking $booking
     * @return bool
     */
    public function isUserTheBookingCustomer(User $user, Booking $booking): bool
    {
        return $user->getId() == $booking->getUserId();
    }

    /**
     * @param User $user
     * @param Booking $booking
     * @return bool
     */
    public function isUserAChosenBookingProvider(User $user, Booking $booking): bool
    {
        return $user->isProvider() &&
        !is_null($this->bookingRequestProviderRepo->getByBookingAndProviderId($booking->getId(), $user->getId()));
    }
}
