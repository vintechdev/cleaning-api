<?php

namespace App\Services;

use App\Services\Bookings\BookingServicesManager;

/**
 * Class CostCalculator
 * @package App\Services
 */
class BookingCostCalculator
{
    /**
     * @var BookingServicesManager
     */
    private $bookingServicesManager;

    public function __construct(
        BookingServicesManager $bookingServicesManager
    ) {
        $this->bookingServicesManager = $bookingServicesManager;
    }

    public function getInitialBookingCostDetails(array $serviceIds, array $providerIds, array $serviceTimes = [])
    {
        $providerBookingServices = [];
        foreach ($providerIds as $providerId) {
            $bookingServices = [];
            foreach ($serviceIds as $serviceId) {
                $bookingServices[$serviceId] = $this
                    ->getBookingService(
                        $serviceId,
                        $providerId,
                        isset($serviceTimes[$serviceId]) ? $serviceTimes[$serviceId] : null
                    );
            }
        }

        return $providerBookingServices;
    }

    public function getBookingService(int $serviceId, int $providerId = null, float $serviceTime = null)
    {
        return $this->buildBookingService($serviceId, $providerId, $serviceTime);
    }

    private function buildBookingService($serviceId, int $providerId = null, float $serviceTime = null)
    {
        $service['id'] = $serviceId;
        $service['initial_number_of_hours'] = $serviceTime ? : 0;
        $service['provider_id'] = $providerId;
        return $this->bookingServicesManager->buildBookingService($service);
    }

//    private function buildBookingServices(array $serviceIds, int $providerId, array $serviceTimes = [])
//    {
//        $services = [];
//        foreach ($serviceIds as $serviceId) {
//            $service['id'] = $serviceId;
//            $service['initial_number_of_hours'] = isset($serviceTimes[$serviceId]) ? $serviceTimes[$serviceId] : 0;
//            $service['provider_id'] = $providerId;
//            $services[] = $service;
//        }
//
//        return $this->bookingServicesManager->buildBookingServices($services);
//    }
}