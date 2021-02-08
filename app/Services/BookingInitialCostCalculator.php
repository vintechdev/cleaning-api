<?php

namespace App\Services;

use App\Bookingservice;
use App\Services\Bookings\BookingServicesManager;

/**
 * Class BookingInitialCostCalculator
 * @package App\Services
 */
class BookingInitialCostCalculator
{
    /**
     * @var BookingServicesManager
     */
    private $bookingServicesManager;

    /**
     * BookingInitialCostCalculator constructor.
     * @param BookingServicesManager $bookingServicesManager
     */
    public function __construct(
        BookingServicesManager $bookingServicesManager
    ) {
        $this->bookingServicesManager = $bookingServicesManager;
    }

    /**
     * @param array $serviceIds
     * @param array $providerIds
     * @param array $serviceTimes
     * @return array
     */
    public function getInitialBookingCostDetails(array $serviceIds, array $providerIds, array $serviceTimes = [])
    {
        $costDetails = [];
        foreach ($providerIds as $providerId) {
            $totalCost = 0;
            $totalHours = 0;
            $bookingServices = [];
            foreach ($serviceIds as $serviceId) {
                $bookingService = $this
                    ->buildBookingService(
                        $serviceId,
                        $providerId,
                        isset($serviceTimes[$serviceId]) ? $serviceTimes[$serviceId] : null
                    );
                $totalCost += $bookingService->getInitialServiceCost();
                $totalHours += $bookingService->getInitialNumberOfHours();
                $bookingServices[] = $bookingService;
            }
            $costDetails[$providerId]['total_cost'] = $totalCost;
            $costDetails[$providerId]['total_hours'] = $totalHours;
            $costDetails[$providerId]['booking_services'] = $bookingServices;
        }

        return $costDetails;
    }

    /**
     * @param array $costDetails
     * @return int
     */
    public function getHighestPricedProviderIdFromCostDetails(array $costDetails): int
    {
        $totalCostByProviders = [];
        foreach ($costDetails as $providerId => $detail) {
            $totalCostByProviders[$providerId] = $detail['total_cost'];
        }

        $highestPricedProviders = $this->findHighestPricedProviders($totalCostByProviders);

        if (count($highestPricedProviders) === 1) {
            return array_keys($highestPricedProviders)[0];
        }

        foreach (array_keys($highestPricedProviders) as $providerId) {
            $maxPriceProvider = $providerId;
            /** @var Bookingservice[] $bookingServices */
            $bookingServices = $costDetails[$providerId]['booking_services'];
            foreach ($bookingServices as $service) {
                if ($service->getService()->isDefaultService()) {
                    if (isset($maxPrice) && $maxPrice <= $service->getInitialServiceCost()) {
                        $maxPriceProvider = $providerId;
                        $maxPrice = $service->getInitialServiceCost();
                    }
                    break;
                }
            }
        }

        return $maxPriceProvider;
    }

    /**
     * @param array $pricesByProvider
     * @return array
     */
    private function findHighestPricedProviders(array $pricesByProvider): array
    {
        $highestPrice = max(array_values($pricesByProvider));
        return array_filter($pricesByProvider, function ($value, $key) use ($highestPrice) {
            if ($value == $highestPrice) {
                return $key;
            }
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * @param $serviceId
     * @param int|null $providerId
     * @param float|null $serviceTime
     * @return \App\Bookingservice
     * @throws Bookings\Exceptions\BookingserviceBuilderException
     */
    private function buildBookingService($serviceId, int $providerId = null, float $serviceTime = null)
    {
        $service['service_id'] = $serviceId;
        $service['initial_number_of_hours'] = $serviceTime ? : 0;
        $service['provider_id'] = $providerId;
        return $this->bookingServicesManager->buildBookingService($service);
    }
}