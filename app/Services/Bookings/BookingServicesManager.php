<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Bookingservice;
use App\Services\Bookings\Builder\BookingserviceBuilder;
use App\Services\Bookings\Exceptions\BookingServicesExistException;
use App\Services\Bookings\Exceptions\BookingServicesManagerException;
use App\Services\Bookings\Validators\BookingServicesArrayValidator;

/**
 * Class BookingServicesManager
 * @package App\Services\Bookings
 */
class BookingServicesManager
{
    /**
     * @var BookingServicesArrayValidator
     */
    private $bookingServicesArrayValidator;

    /**
     * @var BookingserviceBuilder
     */
    private $bookingserviceBuilder;

    /**
     * BookingServicesManager constructor.
     * @param BookingServicesArrayValidator $bookingServicesArrayValidator
     * @param BookingserviceBuilder $bookingserviceBuilder
     */
    public function __construct(
        BookingServicesArrayValidator $bookingServicesArrayValidator,
        BookingserviceBuilder $bookingserviceBuilder
    ) {
        $this->bookingServicesArrayValidator = $bookingServicesArrayValidator;
        $this->bookingserviceBuilder = $bookingserviceBuilder;
    }

    /**
     * @param Booking $booking
     * @param Bookingservice[] $bookingServices
     */
    public function addBookingServices(Booking $booking, array $bookingServices): bool
    {
        if ($booking->getBookingServices()) {
            throw new BookingServicesExistException('Services can not be added because the booking already has services.');
        }

        $this->bookingServicesArrayValidator->setBookingServices($bookingServices)->isValid();
        return $this->add($booking, $bookingServices);
    }

    /**
     * @param Booking $booking
     * @param Bookingservice[] $bookingServices
     * @return bool
     */
    public function updateBookingServices(Booking $booking, array $bookingServices): bool
    {
        /** @var Bookingservice[] $actualBookingServices */
        $actualBookingServices = $booking->getBookingServices();

        $serviceIds = array_map(function (Bookingservice $bookingservice) {
            return $bookingservice->getService()->getId();
        }, $bookingServices);

        $newBookingServices = array_combine($serviceIds, $bookingServices);

        foreach ($actualBookingServices as $bookingService) {
            if (in_array($bookingService->getService()->getId(), $serviceIds)) {
                $service = $newBookingServices[$bookingService->getService()->getId()];
                if (!$service->getFinalNumberOfHours()) {
                    throw new BookingServicesManagerException('Final number of hours missing for booking service');
                }

                $bookingService
                    ->setFinalNumberOfHours($service->getFinalNumberOfHours())
                    ->setFinalServiceCost($service->getFinalServiceCost());

                unset($newBookingServices[$bookingService->getService()->getId()]);
                continue;
            }

            $bookingService->setRemoved(true)->save();
        }

        return $this->add($booking, $newBookingServices, false);
    }

    /**
     * @param Booking $booking
     * @param array $bookingServicesData
     * @return bool
     * @throws Exceptions\BookingserviceBuilderException
     */
    public function addBookingServicesFromArray(Booking $booking, array $bookingServicesData): bool
    {
        return $this->addBookingServices($booking, $this->buildBookingServices($bookingServicesData));
    }

    /**
     * @param Booking $booking
     * @param array $bookingServicesData
     * @return bool
     * @throws Exceptions\BookingserviceBuilderException
     */
    public function updateBookingServicesFromArray(Booking $booking, array $bookingServicesData): bool
    {
        return $this->updateBookingServices($booking, $this->buildBookingServices($bookingServicesData));
    }

    /**
     * @param array $bookingServicesData
     * @return array
     * @throws Exceptions\BookingserviceBuilderException
     */
    public function buildBookingServices(array $bookingServicesData): array
    {
        $bookingServices = [];
        foreach ($bookingServicesData as $bookingServiceDatum) {
            $bookingServices[] = $this->buildBookingService($bookingServiceDatum);
        }

        return $bookingServices;
    }

    /**
     * @param array $data
     * @return Bookingservice
     * @throws Exceptions\BookingserviceBuilderException
     */
    public function buildBookingService(array $data)
    {
        return $this->bookingserviceBuilder->fromArray($data);
    }

    /**
     * @param Booking $booking
     * @param Bookingservice[] $bookingServices
     * @param bool $isInitiallyAdded
     * @return bool
     */
    private function add(Booking $booking, array $bookingServices, bool $isInitiallyAdded = true)
    {
        foreach ($bookingServices as $bookingService) {
            if ($isInitiallyAdded && !$bookingService->getInitialNumberOfHours()) {
                throw new BookingServicesManagerException('Initial number of hours missing for booking service');
            }

            if (!$isInitiallyAdded && !$bookingService->getFinalNumberOfHours()) {
                throw new BookingServicesManagerException('Final number of hours missing for booking service');
            }

            $bookingService
                ->setBookingId($booking->getId())
                ->setInitiallyAdded($isInitiallyAdded);

            if (!$bookingService->save()) {
                return false;
            }
        }

        return true;
    }
}