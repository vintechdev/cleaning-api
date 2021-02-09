<?php


namespace App\Services;

use App\Bookingservice;

/**
 * Class BookingServicesCostCalculator
 * @package App\Services
 */
class BookingServicesCostCalculator
{
    const TOTAL_COST = 'totalcost';
    const TOTAL_HOURS = 'totalhours';

    /**
     * @param Bookingservice[] $bookingServices
     * @return array
     */
    public function getInitialTotalsFromBookingServices(array $bookingServices): array
    {
        return $this->getTotals($bookingServices, true);
    }

    /**
     * @param Bookingservice[] $bookingServices
     * @return array
     */
    public function getFinalTotalsFromBookingServices(array $bookingServices): array
    {
        return $this->getTotals($bookingServices, false);
    }

    /**
     * @param Bookingservice[] $bookingServices
     * @param bool $useInitials
     * @return array
     */
    private function getTotals(array $bookingServices, bool $useInitials = true): array
    {
        $totalCost = 0;
        $totalHours = 0;
        foreach ($bookingServices as $bookingService) {
            if ($bookingService->isRemoved()) {
                continue;
            }
            $totalCost += $useInitials ? $bookingService->getInitialServiceCost() : $bookingService->getFinalServiceCost();
            $totalHours += $useInitials ? $bookingService->getInitialNumberOfHours() : $bookingService->getFinalNumberOfHours();
        }

        return [
            self::TOTAL_COST => $totalCost,
            self::TOTAL_HOURS => $totalHours
        ];
    }
}