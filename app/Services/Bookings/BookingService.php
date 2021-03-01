<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Bookingaddress;
use App\Bookingquestion;
use App\Bookingrequestprovider;
use App\Bookingstatus;
use App\Service;
use App\Servicequestion;
use App\Events\BookingCreated;
use App\Exceptions\Booking\BookingCreationException;
use App\Exceptions\NoSavedCardException;
use App\Plan;
use App\Repository\BookingReqestProviderRepository;
use App\Repository\Eloquent\StripeUserMetadataRepository;
use App\Services\Bookings\Exceptions\BookingserviceBuilderException;
use App\Services\PlansService;
use App\Services\TotalCostCalculation;
use App\User;
use App\Useraddress;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * Class BookingService
 * @package App\Services\Bookings
 */
class BookingService
{
    /**
     * @var StripeUserMetadataRepository
     */
    private $stripeUserMetadataRepository;

    /**
     * @var BookingReqestProviderRepository
     */
    private $requestProviderRepo;

    /**
     * @var BookingServicesManager
     */
    private $bookingServicesManager;

    /**
     * @var TotalCostCalculation
     */
    private $totalCostCalculator;

    /**
     * @var PlansService
     */
    private $planService;

    /**
     * BookingService constructor.
     * @param StripeUserMetadataRepository $stripeUserMetadataRepository
     * @param BookingReqestProviderRepository $reqestProviderRepository
     * @param BookingServicesManager $bookingServicesManager
     * @param TotalCostCalculation $totalCostCalculator
     * @param PlansService $plansService
     */
    public function __construct(
        StripeUserMetadataRepository $stripeUserMetadataRepository,
        BookingReqestProviderRepository $reqestProviderRepository,
        BookingServicesManager $bookingServicesManager,
        TotalCostCalculation $totalCostCalculator,
        PlansService $plansService
    ) {
        $this->stripeUserMetadataRepository = $stripeUserMetadataRepository;
        $this->requestProviderRepo = $reqestProviderRepository;
        $this->bookingServicesManager = $bookingServicesManager;
        $this->totalCostCalculator = $totalCostCalculator;
        $this->planService = $plansService;
    }

    /**
     * TODO: This function is moved from controller. Needs refactoring
     * @param array $bookingAttributes
     * @param User $user
     * @param Booking|null $parent
     * @return Booking
     * @throws NoSavedCardException
     */
    public function createBooking(array $bookingAttributes, User $user, Booking $parent = null)
    {

        //TODO: Remove unwanted columns from bookings table.
        if (!$this->stripeUserMetadataRepository->hasUserSavedCard($user)){
            throw new NoSavedCardException('User does not have a saved card for payment');
        }

        $service = $bookingAttributes['service'];
        $bookings = $bookingAttributes['bookings'];
        $question = $bookingAttributes['question'];
        $providers = $bookingAttributes['provider'];

        $discounts = [];
        if(count($bookings)>0){
            if (!$parent) {
                $serviceIds = [];
                $serviceTimes = [];
                $providerIds = [];

                foreach ($service as $item) {
                    $serviceIds[] = $item['service_id'];
                    $serviceTimes[$item['service_id']] = $item['initial_number_of_hours'];
                    if (!isset($category)) {
                        /** @var Service $service */
                        $service = Service::find($item['service_id']);
                        if (!$service) {
                            throw new \InvalidArgumentException('Invalid service id received');
                        }
                        $category = $service->getCategoryId();
                        $serviceCategory = $service->getServicecategory();
                    }
                }

                if (!$serviceCategory->allowMultipleAddons() && count($serviceIds) > 2) {
                    throw new \InvalidArgumentException('No more that one additional service can be added for this service category.');
                }

                if (!$this->planService->isPlanValidForServiceCategory($bookings['plan_type'], $serviceCategory)) {
                    throw new \InvalidArgumentException('Invalid plan received');
                }

                foreach ($providers as $provider) {
                    $providerIds[] = $provider['provider_user_id'];
                }

                $highestTotalPriceDetails = $this
                    ->totalCostCalculator
                    ->GetHighestTotalPrice(
                        $serviceIds,
                        implode(',', $providerIds),
                        $serviceTimes,
                        $bookings['plan_type'],
                        $bookings['promocode'],
                        $category,
                        true,
                        true
                    );

                $bookingServices = $highestTotalPriceDetails['booking_services'];
                $discounts = $highestTotalPriceDetails['all_discounts'];
            }

            DB::beginTransaction();
            try {
                $booking = new Booking();

                $booking->user_id = $user->getId();
                try {
                    $bookingStatus = ($parent && isset($bookings['booking_status_id'])) ? $bookings['booking_status_id'] : Bookingstatus::BOOKING_STATUS_PENDING;
                    $booking->setStatus($bookingStatus);
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw new \InvalidArgumentException('Invalid booking status');
                }

                $booking->is_recurring = (isset($bookings['is_recurring'])) ? $bookings['is_recurring'] : 0;
                $booking->booking_date = $bookings['booking_date'];
                $booking->booking_time = $bookings['booking_time'];
                $booking->booking_end_time = $bookings['booking_end_time'];
                $booking->booking_postcode = $bookings['booking_postcode'];
                $booking->booking_provider_type = $bookings['booking_provider_type'];
                $booking->plan_type = $bookings['plan_type'];
                $booking->promocode = $bookings['promocode'];
                $booking->total_cost = $parent ? $bookings['total_cost'] : $highestTotalPriceDetails['total_cost'];
                $booking->discount = $parent ? $bookings['discount'] : $highestTotalPriceDetails['discount'];
                $booking->plan_discount = $parent ? $bookings['plan_discount'] : $highestTotalPriceDetails['plan_discount_price'];
              
                $booking->final_cost = $parent ? $bookings['final_cost'] : $highestTotalPriceDetails['final_cost'];
                $booking->final_hours = $parent ? $bookings['final_hours'] : $highestTotalPriceDetails['total_time'];
                $booking->is_flexible = $bookings['is_flexible'];

                if ($parent) {
                    $booking->parent_booking_id = $parent->getId();
                }

                if ($booking->save()) {
                    $last_insert_id = DB::getPdo()->lastInsertId();

                    $address = [];
                    if ($parent) {
                        /** @var Bookingaddress $bookingAddress */
                        $bookingAddress = Bookingaddress::where(['booking_id' => $parent->getId()])->get();
                        if ($bookingAddress) {
                            $address = $bookingAddress->toArray();
                            $address =  $address[0];
                        }

                        $discounts = $parent->getDiscounts()->all();
                    } else {
                        $addresses = Useraddress::where('id', $bookings['addressid'])->get()->toarray();
                        if ($addresses) {
                            $address = $addresses[0];
                        }
                    }


                    if (count($address) > 0) {
                        $bookingaddress = new Bookingaddress();
                        $bookingaddress->booking_id = $last_insert_id;
                        $bookingaddress->address_line1 = $address['address_line1'];
                        if($address['address_line2']!='' && $address['address_line2']!=NULL){
                            $bookingaddress->address_line2 = $address['address_line2'];
                        }
                        $bookingaddress->suburb = $address['suburb'];
                        $bookingaddress->state = $address['state'];
                        $bookingaddress->postcode = $address['postcode'];
                       
                        if (!$bookingaddress->save()) {
                            DB::rollBack();
                            throw new BookingCreationException('Booking address could not be saved');
                        }
                    }

                    if ($discounts) {
                        $booking->addDiscounts($discounts);
                    }

                    //TODO: Validate if these are available providers. We can't pass any providers
                    if (!empty($providers)) {
                        foreach ($providers as $key => $provider) {
                            $bookingrequestprovider = new Bookingrequestprovider();
                            $bookingrequestprovider->booking_id = $last_insert_id;
                            $bookingrequestprovider->provider_user_id = $provider['provider_user_id'];
                            if (!$parent) {
                                $bookingrequestprovider->setStatus(Bookingrequestprovider::STATUS_PENDING);
                            } else {
                                $bookingrequestprovider->setStatus((isset($provider['status']) ? $provider['status'] : Bookingrequestprovider::STATUS_PENDING));
                            }

                            $bookingrequestprovider->provider_comment = $provider['provider_comment'];
                            $bookingrequestprovider->visible_to_enduser = $provider['visible_to_enduser'];
                            if (!$bookingrequestprovider->save()) {
                                DB::rollBack();
                                throw new BookingCreationException('Booking request provider could not be saved');
                            }
                        }
                    }

                    try {
                        if (!$parent) {
                            if (!$service) {
                                // TODO: log error saying that invalid services type received.
                                throw new BookingCreationException('Booking could not be saved without services');
                            }
                        } else {
                            if (!($service instanceof Collection) || !$service->count()) {
                                // TODO: log error saying that invalid services type received.
                                throw new BookingCreationException('Booking service could not be saved');
                            }
                            $bookingServices = [];

                            foreach ($service as $serv) {
                                $bookingServices[] = $serv->replicate();
                            }
                        }
                        $this->bookingServicesManager->addBookingServices($booking, $bookingServices);
                    } catch (BookingserviceBuilderException $exception) {
                        DB::rollBack();
                        throw new BookingCreationException($exception->getMessage());
                    } catch (\Exception $exception) {
                        DB::rollBack();
                        throw new BookingCreationException('Booking service could not be saved');
                    }

                   
                    if($parent){
                        $question = Bookingquestion::where('booking_id',$parent->id)->get()->toarray();
                    }

                    if (!empty($question)) {
                      //  dd($question);
                        foreach ($question as $key => $quest){
                            if ($quest['answer'] != null){

                                
                                if($parent){
                                    $title = $quest['question_title'];
                                    $type = $quest['question_type'];
                                    $service_id = $quest['service_id'];
                                }else{
                                    $questiondata = Servicequestion::where('id',$quest['service_question_id']);
                                    if ($questiondata->count()) {
                                        $questiondata = $questiondata->first()->toArray();
                                        if(count($questiondata)>0){
                                            $title = $questiondata['title'];
                                            $type = $questiondata['question_type'];
                                            $service_id = $questiondata['service_id'];
                                        }
                                    }
                                }

                                if (isset($service_id)) {
                                    $bookingquestion = new Bookingquestion();
                                    $bookingquestion->booking_id = $last_insert_id;
                                    $bookingquestion->question_type = $type;
                                    $bookingquestion->service_id = $service_id;
                                    $bookingquestion->question_title = $title;
                                    $bookingquestion->answer = $quest['answer'];
                                    if (!$bookingquestion->save()){
                                        DB::rollBack();
                                        throw new BookingCreationException('Booking questions could not be saved');
                                    }
                                }
                            }
                        }
                    }

                    DB::commit();
                    if (!$parent) {
                        event(new BookingCreated($booking, $user));
                    }

                    return $booking;
                }
            } catch (\Exception $exception) {
                DB::rollBack();
                throw $exception;
            }
            DB::rollBack();
        }

        throw new \InvalidArgumentException('No booking data received');
    }

    /**
     * @param Booking $booking
     * @param bool $isChild
     * @return Booking
     * @throws NoSavedCardException
     */
    public function createChildBooking(Booking $booking): Booking
    {
        $booking->setPlanType(Plan::ONCEOFF);
        $booking->setRecurring(false);
        $bookingDetails = $booking->toArray();
        $services = $booking->getBookingServices();
        $providers = $this->requestProviderRepo->getAllByBookingId($booking->getId())->toArray();
        $questions = $this->getBookingQuestions($booking->getId());

        return $this->createBooking([
                'service' => $services,
                'provider' => $providers,
                'question' => $questions,
                'bookings' => $bookingDetails,
            ],
            User::find($booking->getUserId()),
            $booking
        );
    }

    /**
     * @param int $id
     * @return array
     */
    public function getBookingAddress(int $id)
    {
        return Bookingaddress::where('booking_id',$id)->get()->toArray();
    }

    /**
     * @param int $id
     * @return array
     */
    public function getBookingQuestions(int $id)
    {
        $questions = Bookingquestion::with('service')->where('booking_id',$id)->get()->toArray();
        $qst = [];
        if(count($questions)>0){
        //  dd($questions);
            foreach($questions as $k=>$v){
                $q =[];
               // $questiondet = 
               if($v['question_type']== 'checkbox'){
                   $answer = str_replace('|',', ',$v['answer']);
                   $answer=  substr(trim($answer),0,-1);
               }else{
                   $answer = $v['answer'];
               }
                $q['question'] =$v['question_title'];
                $q['question_type'] =$v['question_type'];
                $q['answer'] =($answer=='Y')?'Yes':$answer;
                $q['service_id'] = $v['service_id'];
                $q['service_name'] = $v['service']['name'];
                $qst[] = $q;
            }
           
        }
        return $qst;
    }
    public function getBookingsForChat($type)
    {
       // dd($user);
        $user = Auth::user();
        $user_id = $user->id;
       
        if(in_array('provider', $user->getScopes())){
                    $arr = Booking::has('bookingchat', '>' , 0)->with(array('bookingrequestprovider' => function($query) use ($user_id){
                            $query->where('provider_user_id',$user_id)->whereIn('status',['accepted','arrived','completed']);
                        },'bookingchat'=>function($query){
                            $query->orderBy('created_at', 'desc');
                        },'users','bookingrequestprovider.users'))
                        ->with('bookingServices')
                        ->with('bookingServices.service')
                        ->with('bookingServices.service.servicecategory')
                        ->whereIn('bookings.booking_status_id',[2,3,4])->get()
                        ->map(function($bookings) use ($type){
                            if($type=='unread'){
                                $bookings->setRelation('bookingchat', $bookings->bookingchat->where('isread',0)->take(1));
                            }else{
                                $bookings->setRelation('bookingchat', $bookings->bookingchat->take(1));//->orderBy('created_at','desc')
                            }
                            $bookings->setRelation('bookingrequestprovider',$bookings->bookingrequestprovider);//
                            return $bookings;
                        })->toarray();
        }else{

       
                         $arr = Booking::has('bookingchat', '>' , 0)->with(array('bookingrequestprovider' => function($query){
                                $query->whereIn('status',['accepted','arrived','completed']);
                            },'bookingchat'=>function($query){
                                $query->orderBy('created_at', 'desc');//->first();
                            },'bookingrequestprovider.users'))
                            ->with('bookingServices')->with('bookingServices.service')
                            ->with('bookingServices.service.servicecategory')
                            ->where('bookings.user_id',$user_id)
                           // ->where('bookingchat_count','>',0)
                            ->whereIn('bookings.booking_status_id',[2,3,4])->get()//->sortByDesc('bookingchat.created_at')
                            ->map(function($bookings)use ($type){
                                if($type=='unread'){
                                    $bookings->setRelation('bookingchat', $bookings->bookingchat->where('isread',0)->take(1));
                                }else{
                                    $bookings->setRelation('bookingchat', $bookings->bookingchat->take(1));//->orderBy('created_at','desc')
                                }
                                $bookings->setRelation('bookingrequestprovider',$bookings->bookingrequestprovider);//
                                return $bookings;
                            })->toarray();
        }
                      //  $service = $arr->bookingServices;
      
        $data = [];
        if(count($arr)){
            foreach($arr as $k=>$v){
                //dd($v['bookingrequestprovider']);
                if(count($v['bookingchat'])>0){
                $d = [];
                $d['booking_id'] = $v['id'];
                $d['user_id']=$v['user_id'];
                $d['booking_status_id']=$v['booking_status_id'];
                $d['booking_date']=$v['booking_date'];
                $d['booking_time']=$v['booking_time'];
                $d['final_hours']=$v['final_hours'];

                if(count($v['booking_services'])>0){
                        $b = [];
                        $bs = $v['booking_services'][0];
                        if($bs['service']['is_default_service']==1){
                            $b['service_id'] = $bs['service_id'];
                            $b['service_name'] = $bs['service']['name'];
                            $b['category_name'] = $bs['service']['servicecategory']['name'];
                            $d['service'] =$b;
                        }
                }
               // if(in_array('provider', $user->getScopes())){
                    if(array_key_exists('users',$v)){
                        $ur = $v['users'];
                        $u['user_name'] = $ur['first_name'].' '. $ur['last_name'];
                        $u['profilepic']= $ur['profilepic'];
                        $d['users'] = $u;
                    }
               // }
                        $chat = $v['bookingchat'];
                            $c = [];
                            $c['message'] = $chat[0]['message'];
                            $c['created_at'] = $chat[0]['created_at'];
                            $c['sender_id'] = $chat[0]['sender_id'];
                            $c['receiver_id'] = $chat[0]['receiver_id'];
                            $d['chat'] =$c;
                            $d['last_chat_date']= $chat[0]['created_at'];

                            if(count($v['bookingrequestprovider'])>0){
                                $pr = $v['bookingrequestprovider'][0];
                            // dd($pr);
                                $p = [];
                                $p['provider_id'] = $pr['provider_user_id'];
                                $p['status'] = $pr['status'];
                                $user = $pr['users'];
                                $p['provider_name'] = $user['first_name'].' '. $user['last_name'];
                                $p['profilepic'] =  $user['profilepic'];
                                $d['provider'] = $p;
                            }
                        $data[] = $d;
                }
            }
           
        }
        array_multisort(array_map(function($element) {
            return $element['last_chat_date'];
        }, $data), SORT_DESC, $data);
        return $data;
    }
   
    
}