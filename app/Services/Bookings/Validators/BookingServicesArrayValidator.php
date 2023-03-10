<?php

namespace App\Services\Bookings\Validators;

use App\Bookingservice;
use App\Servicecategory;
use App\Services\Bookings\Exceptions\BookingServicesArrayValidatorException;
use App\Services\Bookings\Exceptions\BookingServicesDifferentCategoryException;
use App\Services\Bookings\Exceptions\MissingDefaultServiceException;
use App\Services\Interfaces\ValidatorInterface;

/**
 * Class BookingServicesArrayValidator
 * @package App\Services\Bookings\Validators
 */
class BookingServicesArrayValidator implements ValidatorInterface
{
    /**
     * @var Bookingservice[]
     */
    private $bookingServices;

    /**
     * @param array $bookingServices
     * @return $this
     */
    public function setBookingServices(array $bookingServices)
    {
        $this->bookingServices = $bookingServices;
        return $this;
    }

    /**
     * @return bool
     * @throws BookingServicesDifferentCategoryException
     * @throws MissingDefaultServiceException
     */
    public function isValid(): bool
    {
        $hasDefaultService = false;
        $previousCategoryId = null;
        foreach ($this->bookingServices as $bookingService) {
            $service = $bookingService->getService();
            $categoryId = $service->getServicecategory()->id;
            if ($previousCategoryId && $previousCategoryId != $categoryId) {
                throw new BookingServicesDifferentCategoryException('Services should belong to the same category');
            }
            $previousCategoryId = $categoryId;
            if (!$hasDefaultService) {
                $hasDefaultService = $service->isDefaultService();
            }
        }

        if (!$hasDefaultService) {
            throw new MissingDefaultServiceException('List of service does not have a default service');
        }

        /** @var Servicecategory $category */
        $category = Servicecategory::find($categoryId);
        if (!$category->allowMultipleAddons() && count($this->bookingServices) > 2) {
            throw new BookingServicesArrayValidatorException('Service category does not allow multiple addons');
        }

        return true;
    }
}