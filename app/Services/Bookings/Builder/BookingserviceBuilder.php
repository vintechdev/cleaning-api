<?php

namespace App\Services\Bookings\Builder;

use App\Exceptions\BookingServicesInvalidNumberOfHoursException;
use App\Providerservicemaps;
use App\Service;
use App\Bookingservice;
use App\Repository\ProviderServiceMapRespository;
use App\Services\Bookings\Exceptions\BookingserviceBuilderException;
use Illuminate\Database\Eloquent\Collection;

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
     * @param array $bookingServiceData
     * @param bool $calculateFinalTotal
     * @return Bookingservice
     * @throws BookingserviceBuilderException
     */
    public function fromArray(array $bookingServiceData, bool $calculateFinalTotal = false)
    {
        if (!isset($bookingServiceData['service_id'])) {
            throw new BookingserviceBuilderException('Service id not found');
        }

        $service = Service::find($bookingServiceData['service_id']);

        if (!$service) {
            throw new BookingserviceBuilderException('Invalid service id received: ' . $bookingServiceData['service_id']);
        }

        $bookingService = new Bookingservice();
        $bookingService
            ->setService($service);

        $providerservicemaps = null;
        if (isset($bookingServiceData['provider_id'])) {
            $providerservicemaps = $this->getProviderMap($service, $bookingServiceData['provider_id']);
        }

        if (isset($bookingServiceData['initial_number_of_hours'])) {
            try {
                $bookingService->setInitialNumberOfHours($bookingServiceData['initial_number_of_hours']);
            } catch (BookingServicesInvalidNumberOfHoursException $exception) {
                throw new BookingserviceBuilderException($exception->getMessage());
            }

            if (isset($bookingServiceData['provider_id']) && $providerservicemaps) {
                $bookingService
                    ->setInitialServiceCost($providerservicemaps->getProviderTotal($bookingService->getInitialNumberOfHours()));
            }
        }

        if ($calculateFinalTotal) {
            if (!isset($bookingServiceData['provider_id'])) {
                throw new BookingserviceBuilderException('Provider id is required to determine final cost');
            }

            if (isset($bookingServiceData['final_number_of_hours'])) {
                try {
                    $bookingService->setFinalNumberOfHours($bookingServiceData['final_number_of_hours']);
                } catch (BookingServicesInvalidNumberOfHoursException $exception) {
                    throw new BookingserviceBuilderException($exception->getMessage());
                }
            }

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
        /** @var Collection $providerServiceMap */
        $providerServiceMap = $this
            ->providerServiceMapsRepo
            ->GetServicePriceofProvider([$service->getId()], $providerId, false);

        if (!$providerServiceMap->count()) {
            return null;
        }

        return $providerServiceMap->first();
    }
}