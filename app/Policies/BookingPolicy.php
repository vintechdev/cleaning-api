<?php

namespace App\Policies;

use App\Booking;
use App\Bookingrequestprovider;
use App\Services\Bookings\BookingStatusManager;
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
     * @var BookingStatusManager
     */
    private $bookingManager;

    /**
     * BookingPolicy constructor.
     * @param BookingStatusManager $bookingManager
     */
    public function __construct(BookingStatusManager $bookingManager)
    {
        $this->bookingManager = $bookingManager;
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
        return $this->bookingManager->canUserUpdateBooking($booking, $user);
    }
}
