<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Bookingaddress;
use App\Bookingquestion;
use App\Bookingrequestprovider;
use App\Bookingstatus;
use App\Servicequestion;
use App\Events\BookingCreated;
use App\Exceptions\Booking\BookingCreationException;
use App\Exceptions\NoSavedCardException;
use App\Plan;
use App\Repository\BookingReqestProviderRepository;
use App\Repository\Eloquent\StripeUserMetadataRepository;
use App\Services\Bookings\Exceptions\BookingserviceBuilderException;
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
     * BookingService constructor.
     * @param StripeUserMetadataRepository $stripeUserMetadataRepository
     * @param BookingReqestProviderRepository $reqestProviderRepository
     * @param BookingServicesManager $bookingServicesManager
     */
    public function __construct(
        StripeUserMetadataRepository $stripeUserMetadataRepository,
        BookingReqestProviderRepository $reqestProviderRepository,
        BookingServicesManager $bookingServicesManager
    )
    {
        $this->stripeUserMetadataRepository = $stripeUserMetadataRepository;
        $this->requestProviderRepo = $reqestProviderRepository;
        $this->bookingServicesManager = $bookingServicesManager;
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

        if(count($bookings)>0){
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
                $booking->total_cost = $bookings['total_cost'];
                $booking->discount = $bookings['discount'];
                $booking->plan_discount = $bookings['plan_discount'];
              
                $booking->final_cost = $bookings['final_cost'];
                $booking->final_hours = $bookings['final_hours'];
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
                        $bookingaddress->address_line2 = $address['address_line2'];
                        $bookingaddress->suburb = $address['suburb'];
                        $bookingaddress->state = $address['state'];
                        $bookingaddress->postcode = $address['postcode'];
                       
                        if (!$bookingaddress->save()) {
                            DB::rollBack();
                            throw new BookingCreationException('Booking address could not be saved');
                        }
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
                            $this->bookingServicesManager->addBookingServicesFromArray($booking, $service);
                        } else {
                            if (!($service instanceof Collection) || !$service->count()) {
                                // TODO: log error saying that invalid services type received.
                                throw new BookingCreationException('Booking service could not be saved');
                            }
                            $services = [];

                            foreach ($service as $serv) {
                                $services[] = $serv->replicate();
                            }
                            $this->bookingServicesManager->addBookingServices($booking, $services);
                        }
                    } catch (BookingserviceBuilderException $exception) {
                        DB::rollBack();
                        throw new BookingCreationException($exception->getMessage());
                    } catch (\Exception $exception) {
                        DB::rollBack();
                        throw new BookingCreationException('Booking service could not be saved');
                    }

                   
                    if($parent){
                        $question = Bookingquestion::where('booking_id',$parent->id)->get()->toarray();
                    }else{
                        $question = $question;
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
                                    $questiondata = Servicequestion::where('id',$quest['service_question_id'])->first()->toArray();
                                    
                                    if(count($questiondata)>0){
                                        $title = $questiondata['title'];
                                        $type = $questiondata['question_type'];
                                        $service_id = $questiondata['service_id'];
                                    }
                                }

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

                    DB::commit();
                    event(new BookingCreated($booking, $user));

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
    public function getBookingsForChat()
    {
        $user_id = Auth::user()->id;
        $arr = Booking::with(array('bookingrequestprovider' => function($query){
                                $query->whereIn('status',['accepted','arrived','completed']);
                            },'bookingchat','bookingrequestprovider.users'))
                            ->where('bookings.user_id',$user_id)
                            ->whereIn('bookings.booking_status_id',[2,3,4])->get()
                            ->map(function($bookings) {
                            $bookings->setRelation('bookingchat', $bookings->bookingchat->take(1));//->orderBy
                            $bookings->setRelation('bookingrequestprovider',$bookings->bookingrequestprovider);//
                            return $bookings;
                        })->toarray();

                  
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

                $chat = $v['bookingchat'];
                    $c = [];
                    $c['message'] = $chat[0]['message'];
                    $c['created_at'] = $chat[0]['created_at'];
                    $c['sender_id'] = $chat[0]['sender_id'];
                    $c['receiver_id'] = $chat[0]['receiver_id'];
                    $d['chat'] =$c;

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
        return $data;
    }
}