<?php

namespace App\Services;

use App\Booking;
use App\Discounts;

/**
 * Class BookingFinalCostCalculator
 * @package App\Services
 */
class BookingFinalCostCalculator
{
    /**
     * @var BookingServicesCostCalculator
     */
    private $bookingServicesCostCalculator;

    /**
     * @var DiscountManager
     */
    private $discountManager;

    /**
     * BookingFinalCostCalculator constructor.
     * @param BookingServicesCostCalculator $bookingServicesCostCalculator
     * @param DiscountManager $discountManager
     */
    public function __construct(
        BookingServicesCostCalculator $bookingServicesCostCalculator,
        DiscountManager $discountManager
    ) {
        $this->bookingServicesCostCalculator = $bookingServicesCostCalculator;
        $this->discountManager = $discountManager;
    }

    /**
     * @param Booking $booking
     * @return bool
     */
    public function updateBookingGrandTotal(Booking $booking): bool
    {
        $totals = $this
            ->bookingServicesCostCalculator
            ->getFinalTotalsFromBookingServices($booking->getBookingServices()->all());

        $totalCost = $totals[BookingServicesCostCalculator::TOTAL_COST];
        $totalHours = $totals[BookingServicesCostCalculator::TOTAL_HOURS];

        $planDiscount = 0;
        $promoDiscount = 0;

        /** @var Discounts $discount */
        foreach($booking->getDiscounts()->all() as $discount) {
            if ($discount->isPlanDiscount()) {
                $planDiscount += $this->discountManager->getDiscountAmount($discount, $totalCost);
                continue;
            }

            $promoDiscount += $this->discountManager->getDiscountAmount($discount, $totalCost);
        }

        $booking->plan_discount = $planDiscount;
        $booking->discount = $promoDiscount;
        $booking->total_cost = $totalCost;
        $booking->final_cost = $this->discountManager->getDiscountedPrice($booking->getDiscounts()->all(), $totalCost);
        $booking->final_hours = $totalHours;

        return $booking->save();
    }
}