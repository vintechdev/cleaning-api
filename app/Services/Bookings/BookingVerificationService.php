<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Repository\BookingReqestProviderRepository;
use App\User;

/**
 * Class BookingVerificationService
 * @package App\Services\Bookings
 */
class BookingVerificationService
{
    /**
     * @var BookingReqestProviderRepository
     */
    private $bookingRequestProviderRepo;

    /**
     * BookingUpdateVerificationService constructor.
     * @param BookingReqestProviderRepository $bookingReqestProviderRepository
     */
    public function __construct(BookingReqestProviderRepository $bookingReqestProviderRepository)
    {
        $this->bookingRequestProviderRepo = $bookingReqestProviderRepository;
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

    /**
     * @param User $user
     * @param Booking $booking
     * @return bool
     */
    public function hasUserAcceptedBooking(User $user, Booking $booking): bool
    {
        if (!$user->isProvider()) {
            return false;
        }

        $requestProvider = $this->bookingRequestProviderRepo->getAcceptedBookingRequestProvider($booking);
        if (!$requestProvider) {
            return false;
        }

        return $requestProvider->getProviderId() == $user->getId();
    }
}