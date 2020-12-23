<?php

namespace App\Policies;

use App\Booking;
use App\Services\Bookings\BookingVerificationService;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class BookingPolicy
 * @package App\Policies
 */
class BookingPolicy
{
    use HandlesAuthorization;

    /**
     * @var BookingVerificationService
     */
    private $bookingVerificationService;

    /**
     * BookingPolicy constructor.
     * @param BookingVerificationService $bookingVerificationService
     */
    public function __construct(BookingVerificationService $bookingVerificationService)
    {
        $this->bookingVerificationService = $bookingVerificationService;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Booking  $booking
     * @return mixed
     */
    public function update(User $user, Booking $booking)
    {
        return $this->bookingVerificationService->canUserUpdateBooking($booking, $user);
    }
}
