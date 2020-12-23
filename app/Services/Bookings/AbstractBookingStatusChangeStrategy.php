<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Repository\BookingReqestProviderRepository;
use App\Services\Bookings\Interfaces\BookingStatusChangeStrategyInterface;
use App\User;

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
     * @param User $user
     * @param Booking $booking
     * @return bool
     */
    protected function isUserAChosenBookingProvider(User $user, Booking $booking): bool
    {
        return $this->bookingVerificationService->isUserAChosenBookingProvider($user, $booking);
    }
}