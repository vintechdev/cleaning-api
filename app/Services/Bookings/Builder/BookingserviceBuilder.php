<?php

namespace App\Services\Bookings\Builder;

use App\Providerservicemaps;
use App\Service;
use App\Bookingservice;
use App\Repository\ProviderServiceMapRespository;
use App\Services\Bookings\Exceptions\BookingserviceBuilderException;

/**
 * Class BookingserviceBuilder
 * @package App\Services\Bookings\Builder
 */
class BookingserviceBuilder
{
    /**
     * @var ProviderServiceMapRespository
     */
    private $providerServiceMapsRepo;

    /**
     * BookingserviceBuilder constructor.
     * @param ProviderServiceMapRespository $providerServiceMapRespository
     */
    public function __construct(ProviderServiceMapRespository $providerServiceMapRespository)
    {
        $this->providerServiceMapsRepo = $providerServiceMapRespository;
    }

    /**
     * @param array $bookingService
     * @return Bookingservice
     * @throws BookingserviceBuilderException
     */
    public function fromArray(array $bookingService)
    {
        if (!isset($bookingService['service_id'])) {
            throw new BookingserviceBuilderException('Service id not found');
        }

        $service = Service::find($bookingService['service_id']);

        if (!$service) {
            throw new BookingserviceBuilderException('Invalid service id received: ' . $bookingService['service_id']);
        }

        $bookingService = new Bookingservice();
        $bookingService
            ->setService($service);

        $providerservicemaps = null;
        if (isset($bookingService['provider_id'])) {
            $providerservicemaps = $this->getProviderMap($service, $bookingService['provider_id']);
        }

        if (isset($bookingService['initial_number_of_hours'])) {
            $bookingService->setInitialNumberOfHours($bookingService['initial_number_of_hours']);
            if (isset($bookingService['provider_id']) && $providerservicemaps) {
                $bookingService
                    ->setInitialServiceCost($providerservicemaps->getProviderTotal($bookingService->getInitialNumberOfHours()));
            }
        }

        if (isset($bookingService['final_number_of_hours'])) {
            if (!isset($bookingService['provider_id'])) {
                throw new BookingserviceBuilderException('Provider id is required to determine final cost');
            }
            $bookingService->setFinalNumberOfHours($bookingService['final_number_of_hours']);

            if ($providerservicemaps) {
                $bookingService
                    ->setFinalServiceCost($providerservicemaps->getProviderTotal($bookingService->getFinalNumberOfHours()));
            }
        }

        return $bookingService;
    }

    /**
     * @param Service $service
     * @param int $providerId
     * @return Providerservicemaps|null
     */
    private function getProviderMap(Service $service, int $providerId): ?Providerservicemaps
    {
        $providerServiceMap = $this
            ->providerServiceMapsRepo
            ->GetServicePriceofProvider([$service->getId()], $providerId);

        if (!$providerServiceMap) {
            return null;
        }

        $providerServiceMap = array_values($providerServiceMap);

        return $providerServiceMap[0];
    }
}