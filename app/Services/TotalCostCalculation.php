<?php
namespace App\Services;
use App\Bookingservice;
use App\Service;
use Illuminate\Http\Request;

class TotalCostCalculation{

    /**
     * @var BookingInitialCostCalculator
     */
    private $bookingInitialCostCalculator;

    /**
     * @var DiscountManager
     */
    private $discountManager;

    public function __construct(BookingInitialCostCalculator $bookingInitialCostCalculator, DiscountManager $discountManager)
    {
        $this->bookingInitialCostCalculator = $bookingInitialCostCalculator;
        $this->discountManager = $discountManager;
    }

    public function GetHighestTotalPrice($serviceid,$provider_id='',$servicetime,$plan_id='',$promocode='',$categoryid='', $returnBookingServices = false)
    {
        if(!is_array($provider_id)) {
            $provider_id = explode(',',$provider_id);
        }

        if (!is_array($serviceid)) {
            $servicetime = [$serviceid => $servicetime];
            $serviceid = [$serviceid];
        }

        $costDetails = $this
            ->bookingInitialCostCalculator
            ->getInitialBookingCostDetails($serviceid, $provider_id, $servicetime);

        $highestPricedProvider = $this->bookingInitialCostCalculator->getHighestPricedProviderIdFromCostDetails($costDetails);
        $totalCost = $costDetails[$highestPricedProvider]['total_cost'];

        $planDiscountDetails = [];
        if ($plan_id) {
            $planDiscountDetails = $this->discountManager->getPlanDiscountDetails($plan_id);
        }

        $result = [];

        $result['service_prices'] = [];

        /** @var Bookingservice $bookingService */
        foreach ($costDetails[$highestPricedProvider]['booking_services'] as $bookingService) {
            $result['service_details'][$bookingService->getService()->getId()] = [
                'service_base_cost' => $bookingService->getBaseInitialServiceCost(),
                'total_hours' => $bookingService->getInitialNumberOfHours(),
                'service_cost' => $bookingService->getInitialServiceCost()
            ];
        }

        if ($returnBookingServices) {
            $result['booking_services'] = $costDetails[$highestPricedProvider]['booking_services'];
        }

        if ($planDiscountDetails) {
            $result['plan_discount_price'] = $this->discountManager->getDiscountAmount($planDiscountDetails, $totalCost);
            $result['plan_discount_type'] = $planDiscountDetails[DiscountManager::DISCOUNT_TYPE];
            $result['plan_discount'] = $planDiscountDetails[DiscountManager::DISCOUNT_VALUE];
            $result['final_cost'] = $this->discountManager->getDiscountedPrice($planDiscountDetails, $totalCost);
        } else {
            $result['plan_discount_price'] = '';
            $result['plan_discount_type'] = '';
            $result['plan_discount'] = '';
            $result['final_cost'] = $totalCost;
        }

        $promoDiscountDetails = [];
        if ($promocode) {
            $promoDiscountDetails = $this->discountManager->getPromoCodeDetails($promocode, $categoryid);
        }

        if ($promoDiscountDetails) {
            $result['discount'] = $this->discountManager->getDiscountAmount($promoDiscountDetails, $result['final_cost']);
            $result['final_cost'] = $this->discountManager->getDiscountedPrice($promoDiscountDetails, $result['final_cost']);
        } else {
            $result['discount'] = '';
        }

        $result['total_cost'] = $totalCost;
        $result['total_time'] = $costDetails[$highestPricedProvider]['total_hours'];
        return $result;
    }

    /**
     * @param $serviceid
     * @param $servicetime
     * @return array
     */
    public function getServicePriceDetails($serviceid, $servicetime)
    {
        /** @var Service $service */
        $service = Service::find($serviceid);
        if (!$service) {
            return [];
        }

        return [
            'total_cost' => $service->getTotalCost($servicetime),
            'total_time' => $servicetime
        ];
    }

    public function PromoCodeDiscount(Request $request){
       
       
     
        $id = $request->get('serviceid');
        $servicetime = $request->get('servicetime');
        
        $promocode = $request->promocode;
        $categoryid = $request->servicecategory;
        $plan_id = $request->plan_id;
       
        $result=array();
       
        $arr = $this->discountManager->getPromoCodeDetails($promocode,$categoryid);
        if(!empty($arr)){
            return response()->json(['data' => 'success'],200);
         }else{
            return response()->json( ['error' => 'Promocode is not valid'],201);
         }
    }
}

?>