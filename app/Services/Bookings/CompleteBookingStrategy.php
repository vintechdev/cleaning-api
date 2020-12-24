<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Bookingrequestprovider;
use App\Bookingservice;
use App\Bookingstatus;
use App\Exceptions\Booking\BookingStatusChangeException;
use App\Exceptions\Booking\InvalidBookingStatusActionException;
use App\Exceptions\Booking\UnauthorizedAccessException;
use App\User;

/**
 * Class CompleteBookingStrategy
 * @package App\Services\Bookings
 */
class CompleteBookingStrategy extends AbstractBookingStatusChangeStrategy
{
    /**
     * @var array
     */
    private $services = [];

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
        if (!$this->canUserCompleteBooking($booking, $user)) {
            throw new UnauthorizedAccessException('User does not have access to this function');
        }

        $this->updateBookingServices($booking);

        if (!$booking->setStatus(Bookingstatus::BOOKING_STATUS_COMPLETED)->save()) {
            throw new BookingStatusChangeException('Unable to save booking status');
        }

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
     * @param array $services
     * @return CompleteBookingStrategy
     */
    public function setServicesDetails(array $services): CompleteBookingStrategy
    {
        $this->services = $services;
        return $this;
    }

    /**
     * @param Booking $booking
     * @return bool
     */
    private function updateBookingServices(Booking $booking): bool
    {
        if (!$this->services) {
            return true;
        }

        if (!$booking->getBookingServices()->count()) {
            throw new InvalidBookingStatusActionException('No services found for this booking');
        }

        $bookingServices = $booking->getBookingServices();

        $serviceIds = array_map(function ($service) {
            return $service['service_id'];
        }, $this->services);

        $services = array_combine($serviceIds, $this->services);

        /** @var Bookingservice $bookingService */
        foreach ($bookingServices as $bookingService) {
            if (in_array($bookingService->getService()->getId(), $serviceIds)) {
                $service = $services[$bookingService->getService()->getId()];
                if (isset($service['final_number_of_hours'])) {
                    $bookingService->setFinalNumberOfHours($service['final_number_of_hours']);
                    $bookingService->updateFinalTotal()->save();
                }
                continue;
            }
            $bookingService->setRemoved(true)->save();
        }

        return true;
    }
}