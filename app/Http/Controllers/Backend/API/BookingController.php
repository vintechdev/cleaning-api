<?php

namespace App\Http\Controllers\Backend\API;

use App\Bookingstatus;
use App\Events\BookingCreated;
use App\Exceptions\Booking\BookingCreationException;
use App\Exceptions\Booking\BookingStatusChangeException;
use App\Exceptions\Booking\InvalidBookingStatusActionException;
use App\Exceptions\Booking\InvalidBookingStatusException;
use App\Exceptions\Booking\RecurringBookingStatusChangeException;
use App\Exceptions\Booking\UnauthorizedAccessException;
use App\Exceptions\NoSavedCardException;
use App\Exceptions\RecurringBookingCreationException;
use App\Service;
use App\Booking;
use App\Bookingaddress;
use App\Bookingquestion;
use App\Bookingservice;
use App\Customermetadata;
use App\Services\Bookings\BookingService as BookingServiceAlias;
use App\Services\Bookings\BookingStatusChangeContext;
use App\Services\Bookings\BookingStatusChangeFactory;
use App\Services\Bookings\Builder\BookingStatusChangeContextBuilder;
use App\Services\RecurringBookingService;
use App\User;
use App\Useraddress;
use App\Payment;
use App\Bookingchange;
use App\OnceBookingAlternateDate;
use App\Bookingrequestprovider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\BookingRequest;
use App\Http\Resources\BookingCollection;
use App\Http\Resources\Booking as BookingResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Repository\BookingServiceRepository;
use App\Repository\BookingReqestProviderRepository;
use Auth;
use Hash;
use DB;
use Input;
use App\Services\TotalCostCalculation;
use App\Repository\Eloquent\StripeUserMetadataRepository;
use App\Repository\ProviderBadgeReviewRepository;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function provider_select_alternate_date(Request $request,$uuid)
    {
            $validator = Validator::make($request->all(), [
            'booking_date' => 'required|date',
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }
        $user = Auth::user();
        $provider_id = $user->id;
        $data = DB::table('booking_request_providers')
            ->join('bookings', 'bookings.id', '=', 'booking_request_providers.booking_id')
            ->select('booking_request_providers.status')
            ->where('bookings.uuid', $uuid)
            ->where('booking_request_providers.provider_user_id', $provider_id)
            ->get();
        // print_r($data);exit;

        if($data[0]->status == "accepted"){
            $booking= Booking::firstOrNew(['uuid' => $uuid]);
            $booking->booking_date = $request->get('booking_date');
            $booking->save();
           return response()->json(['no_content' => true], 200);
        }
        else{
            if ($data[0]->status == "missed" OR $data[0]->status == "rejected") {

               return response()->json(['message' => 'Failed to select alternate date.'],404);
            }
            else{
                return response()->json(['message' => 'Your booking request is pending please accept request.'],404);    
            }
            
        }    
    }

    public function get_alternate_date(Request $request,$uuid)
    {
           $AlternateDates = DB::table('once_booking_alternate_dates')
            ->join('bookings', 'bookings.id', '=', 'once_booking_alternate_dates.booking_id')
            ->select('once_booking_alternate_dates.uuid','once_booking_alternate_dates.booking_id','once_booking_alternate_dates.booking_date')
            ->where('bookings.uuid',$uuid)
            ->get();

        return response()->json(['data' => $AlternateDates]);    
    }

    public function add_alternate_date(Request $request)
    {
                        $record = $request->alternatedate;
                        if(! empty($record))
                        {  
                            foreach($record as $key => $alternatedate)
                            {    
                                $OnceBookingAlternateDate = new OnceBookingAlternateDate;
                                $OnceBookingAlternateDate->booking_id = $alternatedate['booking_id'];
                                $OnceBookingAlternateDate->booking_date = $alternatedate['booking_date'];
                                $OnceBookingAlternateDate->save();
                                       
                            }
                            $responseCode = $request->get('id') ? 200 : 201;
                            return response()->json(['saved' => true], $responseCode);
                        }
                        else{
                                return response()->json(['saved' => false], 404);
                            }
    }

    public function delete_alternate_date(Request $request,$uuid)
    {
        $OnceBookingAlternateDate = OnceBookingAlternateDate::where('uuid',$uuid)->orWhere('id',$uuid)->first();
        if ($OnceBookingAlternateDate != null) {
            $OnceBookingAlternateDate->delete();
            return response()->json(['no_content' => $OnceBookingAlternateDate], 200);
        }
        else{
            return response()->json(['no_content' => 'Wrong ID!!']);
        }
    }

    public function edit_alternate_date(Request $request,$uuid)
    {

        $validator = Validator::make($request->all(), [
            // 'booking_id' => 'required|numeric',
            'booking_date' => 'required|date',
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

        $OnceBookingAlternateDate= OnceBookingAlternateDate::firstOrNew(['uuid' => $uuid]);
        // $OnceBookingAlternateDate->booking_id = $request->get('booking_id');
        $OnceBookingAlternateDate->booking_date = $request->get('booking_date');
        $OnceBookingAlternateDate->save();
        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => $OnceBookingAlternateDate], $responseCode);
    }

    public function promocode_discount(Request $request)
    { 
        
        $result = app(TotalCostCalculation::class)->PromoCodeDiscount($request);
        return response()->json($result);

    }

    public function promocode_discount2(Request $request)
    {

       
        
         return response()->json();

    }
    public function index($uuid,$uuid1)
    {

        $bookings = Booking::where('user_id',$uuid)->where('id',$uuid1);

        // $bookings = $bookings->where('id',$uid)->where('booking_status_id',$id);

        // if ($request->has('id')) {
        //     $bookings = $bookings->where('id', 'LIKE', '%'.$request->get('id'));
        // }
        // if ($request->has('user_id')) {
        //     $bookings = $bookings->where('user_id', 'LIKE', '%'.$request->get('user_id').'%');
        // }
        // if ($request->has('booking_status_id')) {
        //     $bookings = $bookings->where('booking_status_id', 'LIKE', '%'.$request->get('booking_status_id').'%');
        // }
        // if ($request->has('description')) {
        //     $bookings = $bookings->where('description', 'LIKE', '%'.$request->get('description').'%');
        // }
        // if ($request->has('is_recurring')) {
        //     $bookings = $bookings->where('is_recurring', 'LIKE', '%'.$request->get('is_recurring').'%');
        // }
        // if ($request->has('parent_event_id')) {
        //     $bookings = $bookings->where('parent_event_id', 'LIKE', '%'.$request->get('parent_event_id').'%');
        // }
        // if ($request->has('booking_datetime')) {
        //     $bookings = $bookings->where('booking_datetime', 'LIKE', '%'.$request->get('booking_datetime').'%');
        // }
        // if ($request->has('booking_postcode')) {
        //     $bookings = $bookings->where('booking_postcode', 'LIKE', '%'.$request->get('booking_postcode').'%');
        // }
        // if ($request->has('booking_provider_type')) {
        //     $bookings = $bookings->where('booking_provider_type', 'LIKE', '%'.$request->get('booking_provider_type').'%');
        // }
        // if ($request->has('plan_type')) {
        //     $bookings = $bookings->where('plan_type', 'LIKE', '%'.$request->get('plan_type').'%');
        // }
        // if ($request->has('promocode')) {
        //     $bookings = $bookings->where('promocode', 'LIKE', '%'.$request->get('promocode').'%');
        // }
        // if ($request->has('completed_work_total_cost')) {
        //     $bookings = $bookings->where('completed_work_total_cost', 'LIKE', '%'.$request->get('completed_work_total_cost').'%');
        // }
        // if ($request->has('completed_work_service_fee')) {
        //     $bookings = $bookings->where('completed_work_service_fee', 'LIKE', '%'.$request->get('completed_work_service_fee').'%');
        // }
        $bookings = $bookings->paginate(20);
        return (new BookingCollection($bookings));
    }


     public function add_booking(Request $request, BookingServiceAlias $bookingService)
    {
        $user=auth('api')->user();

        try {
            $booking = $bookingService->createBooking($request->all(), $user);
        } catch (\InvalidArgumentException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        } catch (NoSavedCardException $exception) {
            return response()->json(['message' => 'No saved card found.'], 402);
        } catch (BookingCreationException $exception) {
            return response()->json(['message' => 'Something went wrong. Please contact administrator.'], 500);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Something went wrong. Please contact administrator.'], 500);
        }

        return response()->json(['booking' => new BookingResource($booking)], 201);
    }

    /**
     * @param Request $request
     * @param Booking $booking
     * @param BookingStatusChangeContextBuilder $contextBuilder
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBooking(Request $request, Booking $booking, BookingStatusChangeContextBuilder $contextBuilder)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:cancelled,rejected,accepted,arrived,completed',
            'status_change_message' => 'string',
            'services' => 'array'
        ]);

        if($validator->fails()) {
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

        return $this->changeBookingStatus($request, $booking, $contextBuilder);
    }

    public function updateRecurredBooking(
        Request $request,
        Booking $booking,
        string $date,
        RecurringBookingService $recurringBookingService,
        BookingStatusChangeContextBuilder $contextBuilder
    ) {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:cancelled,rejected,accepted,arrived,completed',
            'status_change_message' => 'string',
            'services' => 'array'
        ]);

        if($validator->fails()) {
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }
        try {
            $recurringBooking = $recurringBookingService->findOrCreateRecurringBooking($booking, Carbon::createFromFormat('dmYHis', $date));
        } catch (\InvalidArgumentException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        } catch (RecurringBookingCreationException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Something went wrong. Please contact administrtor.'], 500);
        }

        return $this->changeBookingStatus($request, $recurringBooking->getBooking(), $contextBuilder);
    }

    private function changeBookingStatus(Request $request, Booking $booking, BookingStatusChangeContextBuilder $contextBuilder)
    {
        try {
            $context = $contextBuilder->buildContext($request->get('status'), $request->all());
        } catch (InvalidBookingStatusException $exception) {
            return response()->json(['message' => 'Invlaid booking status received'], 400);
        }

        try {
            $context->changeStatus($booking, auth('api')->user());
        } catch (UnauthorizedAccessException $exception) {
            return response()->json(['message' => $exception->getMessage()], 403);
        } catch (InvalidBookingStatusActionException $exception) {
            return response()->json(['message' => $exception->getMessage()], 403);
        } catch (InvalidBookingStatusException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        } catch (RecurringBookingStatusChangeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 403);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Something went wrong. Please contact administrator.'], 500);
        }

        return response()->json(['booking' => new BookingResource($booking)], 201);
    }

    // for customer get appointment
    public function getappointment(Request $request, $uuid)
    {
        $user = Auth::user();
        $user_id = $user->id;
        // dd($user_id);

        $data['bookings'] = DB::table('bookings')
                                    ->join('booking_status', 'bookings.booking_status_id', '=', 'booking_status.id')
                                    ->select('bookings.id', 'bookings.uuid', 'bookings.booking_date', 'bookings.booking_time', 'bookings.booking_end_time', 'booking_status.status as booking_status')
                                    ->where('bookings.uuid', $uuid)
                                    ->get();

        $data['provider_details'] = DB::table('bookings')
                                    ->join('booking_request_providers', 'bookings.id', '=', 'booking_request_providers.booking_id')
                                    ->join('users', 'booking_request_providers.provider_user_id', '=', 'users.id')
                                    ->select('users.first_name as provider_first_name', 'users.last_name as provider_last_name', 'users.mobile_number as provider_mobile_number', 'users.email as provider_email', 'users.profilepic as provider_profilepic')
                                    ->where('bookings.uuid', $uuid)
                                    ->where('booking_request_providers.status', 'accepted')
                                    ->get();

        $data['appointment_details'] = DB::table('bookings')
                                            ->join('booking_services', 'bookings.id', '=', 'booking_services.booking_id')
                                            ->join('services', 'booking_services.service_id', '=', 'services.id')
                                            ->select('bookings.booking_time', 'services.name as service_name', 'booking_services.initial_number_of_hours', 'booking_services.initial_service_cost')
                                            ->where('bookings.uuid', $uuid)
                                            ->get();
        // print_r($users);exit;

        return response()->json(['data' => $data]);
    }

    // for provider get appointment
    public function provider_getappointment(Request $request, $uuid)
    {
        $user = Auth::user();
        $user_id = $user->id;
        // dd($user_id);

        $data['bookings'] = DB::table('bookings')
                                    ->join('booking_status', 'bookings.booking_status_id', '=', 'booking_status.id')
                                    ->select('bookings.id', 'bookings.uuid', 'bookings.booking_date', 'bookings.booking_time', 'bookings.booking_end_time', 'booking_status.status as booking_status')
                                    ->where('bookings.uuid', $uuid)
                                    ->get();

        $data['customer_details'] = DB::table('bookings')
                                    ->join('users', 'bookings.user_id', '=', 'users.id')
                                    ->select('users.first_name as customer_first_name', 'users.last_name as customer_last_name', 'users.mobile_number as customer_mobile_number', 'users.email as customer_email', 'users.profilepic as customer_profilepic')
                                    ->where('bookings.uuid', $uuid)
                                    ->get();

        $data['appointment_details'] = DB::table('bookings')
                                            ->join('booking_services', 'bookings.id', '=', 'booking_services.booking_id')
                                            ->join('services', 'booking_services.service_id', '=', 'services.id')
                                            ->select('bookings.booking_time', 'services.name as service_name', 'booking_services.initial_number_of_hours', 'booking_services.initial_service_cost')
                                            ->where('bookings.uuid', $uuid)
                                            ->get();
        // print_r($users);exit;

        return response()->json(['data' => $data]);
    }

    //for get alternate booking details
    public function getalterbookdata(Request $request, $uuid)
    {
        $user = Auth::user();
        $user_id = $user->id;
        // print_r($user_id);exit;

        $data["booking_details"] = DB::table('bookings')
        ->join('booking_services', 'bookings.id', '=', 'booking_services.booking_id')
        ->join('services', 'booking_services.service_id', '=', 'services.id')
        ->select('services.name as service_name', 'bookings.booking_date', 'bookings.booking_time', 'bookings.plan_type')
        ->where('bookings.uuid', $uuid)
        ->where('user_id', $user_id)
        ->get();

        $data["alternative_starting_dates"] = DB::table('bookings')
            ->join('once_booking_alternate_dates', 'bookings.id', '=', 'once_booking_alternate_dates.booking_id')
            ->select('once_booking_alternate_dates.booking_date as alternate_bookdate')
            ->where('bookings.uuid', $uuid)
            ->where('user_id', $user_id)
            ->get();

        $data["selected_cleaners"] = DB::table('bookings')
        ->join('booking_request_providers', 'bookings.id', '=', 'booking_request_providers.booking_id')
        ->join('users', 'booking_request_providers.provider_user_id', '=', 'users.id')
        ->join('provider_service_maps', 'booking_request_providers.provider_user_id', '=', 'provider_service_maps.provider_id')
        ->select('users.first_name as provider_firstname', 'users.last_name as provider_lastname', 'provider_service_maps.amount')
        ->where('bookings.uuid', $uuid)
        ->where('user_id', $user_id)
        ->get();
        // print_r($data);exit;

        return response()->json(['data' => $data]);

    }

    //for get booking details
    public function getallbookingdetails(Request $request)
    {

        $user = Auth::user();
        $user_id = $user->id;
        // print_r($user_id);exit;

        $data = DB::table('bookings')
            ->join('booking_status', 'bookings.booking_status_id', '=', 'booking_status.id')
			 ->join('booking_request_providers', 'bookings.id', '=', 'booking_request_providers.booking_id')
			 ->join('users', 'booking_request_providers.provider_user_id', '=', 'users.id')
			 ->join('booking_services', 'bookings.id', '=', 'booking_services.booking_id')
			 ->join('services', 'booking_services.service_id', '=', 'services.id')
             ->select('bookings.*', 'booking_status.status as booking_status','users.first_name as provider_first_name','users.last_name as provider_last_name', 'users.profilepic as provider_profilepic', 'services.name as service_name', 'booking_services.initial_number_of_hours', 'booking_services.initial_service_cost')
            ->where('user_id', $user_id)
            ->get();
        // print_r($data);exit;

        return response()->json(['data' => $data]);

    }
    public function getallcalendardetails(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        // print_r($user_id);exit;
        $date=$request->get('date');

        $data = DB::table('bookings')
            ->join('booking_status', 'bookings.booking_status_id', '=', 'booking_status.id')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->join('booking_services', 'bookings.id', '=', 'booking_services.booking_id')
            ->join('services', 'booking_services.service_id', '=', 'services.id')
            ->select('bookings.booking_datetime', 'booking_status.status as booking_status','services.name','users.first_name','users.last_name')
            ->where('user_id', $user_id)
            ->where('booking_datetime', 'LIKE', '%'.$date.'%')
            ->get();
        // print_r($users);exit;

        return response()->json(['data' => $data]);

    }

    //for get pending booking details
    public function getpendingbookingdetails(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        // print_r($user_id);exit;

        $data = DB::table('bookings')
            ->join('booking_status', 'bookings.booking_status_id', '=', 'booking_status.id')
            ->join('booking_request_providers', 'bookings.id', '=', 'booking_request_providers.booking_id')
			->join('users', 'booking_request_providers.provider_user_id', '=', 'users.id')
            ->join('booking_services', 'bookings.id', '=', 'booking_services.booking_id')
            ->join('services', 'booking_services.service_id', '=', 'services.id')
            ->select('bookings.*', 'booking_status.status as booking_status','users.first_name as provider_first_name','users.last_name as provider_last_name', 'users.profilepic as provider_profilepic', 'services.name as service_name', 'booking_services.initial_number_of_hours', 'booking_services.initial_service_cost')
            ->where('user_id', $user_id)
            ->where('booking_status_id', 1)
            ->where('booking_request_providers.status', 'pending')
            ->get();
        // print_r($users);exit;

        return response()->json(['data' => $data]);

    }

    //for get past booking details
    public function getpastbookingdetails(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        // print_r($user_id);exit;

        $data = DB::table('bookings')
            ->join('booking_status', 'bookings.booking_status_id', '=', 'booking_status.id')
            ->join('booking_request_providers', 'bookings.id', '=', 'booking_request_providers.booking_id')
			->join('users', 'booking_request_providers.provider_user_id', '=', 'users.id')
            ->join('booking_services', 'bookings.id', '=', 'booking_services.booking_id')
            ->join('services', 'booking_services.service_id', '=', 'services.id')
            ->select('bookings.*', 'booking_status.status as booking_status','users.first_name as provider_first_name','users.last_name as provider_last_name', 'users.profilepic as provider_profilepic', 'services.name as service_name', 'booking_services.final_number_of_hours', 'booking_services.final_service_cost')
            ->where('user_id', $user_id)
            ->wherein('booking_status_id', [4,5,6])
            ->where('booking_request_providers.status', 'accepted')
            ->get();
        // print_r($users);exit;

        return response()->json(['data' => $data]);

    }

    public function getbookingdetails(Request $request){
       
        if(!$request->has('id')){
           return  response()->json(['data' => 'id not found'], 404);
        }

      
        $user = Auth::user();
        $user_id = $user->id;
        $id = $request->id;
        // print_r($user_id);exit;

        $data = Booking::join('booking_status', 'bookings.booking_status_id', '=', 'booking_status.id')
                ->join('plans','bookings.plan_type','=','plans.id')
                ->select('bookings.*','plans.plan_name')
                ->where('bookings.id', $id)
                ->get();
       

        $services = app(BookingServiceRepository::class)->getServiceDetails($id);
        $providerscount = app(BookingReqestProviderRepository::class)->getBookingProvidersCount($id);
        if( $providerscount[0]['accepted_count']>0){
            $providers = app(BookingReqestProviderRepository::class)->getBookingAccptedProvidersDetails($id);
        }else{
            $providers = app(BookingReqestProviderRepository::class)->getBookingPendingProvidersDetails($id);
        }

        if(count($providers)>0){
            foreach($providers as $key=>$val){
                $providers[$key]['badges'] = app(ProviderBadgeReviewRepository::class)->getBadgeDetails($val['provider_user_id']);
                $providers[$key]['review'] = app(ProviderBadgeReviewRepository::class)->getReviewDetails($val['provider_user_id']);
                $providers[$key]['avgrate'] = app(ProviderBadgeReviewRepository::class)->getAvgRating($val['provider_user_id']);
            
            }

        }
      // dd($data);
        return response()->json(['data' => $data,'services'=>$services,'providers'=>$providers,'providerscount'=>$providerscount]);

    }

    //for get future booking details
    public function getfuturebookingdetails(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        // print_r($user_id);exit;

        $data = DB::table('bookings')
            ->join('booking_status', 'bookings.booking_status_id', '=', 'booking_status.id')
			->join('booking_request_providers', 'bookings.id', '=', 'booking_request_providers.booking_id')
			->join('users', 'booking_request_providers.provider_user_id', '=', 'users.id')
            ->join('booking_services', 'bookings.id', '=', 'booking_services.booking_id')
            ->join('services', 'booking_services.service_id', '=', 'services.id')
            ->select('bookings.*', 'booking_status.status as booking_status','users.first_name as provider_first_name','users.last_name as provider_last_name', 'users.profilepic as provider_profilepic', 'services.name as service_name', 'booking_services.initial_number_of_hours', 'booking_services.initial_service_cost')
            ->where('user_id', $user_id)
            ->wherein('booking_status_id', [1,2,3])
            ->where('booking_request_providers.status', 'accepted')
            ->get();
        // print_r($users);exit;

        return response()->json(['data' => $data]);

    }

    //for get approved booking details
    public function getapprovedbookingdetails(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        // print_r($user_id);exit;

        $data = DB::table('bookings')
            ->join('booking_status', 'bookings.booking_status_id', '=', 'booking_status.id')
            ->join('booking_request_providers', 'bookings.id', '=', 'booking_request_providers.booking_id')
			->join('users', 'booking_request_providers.provider_user_id', '=', 'users.id')
            ->join('booking_services', 'bookings.id', '=', 'booking_services.booking_id')
            ->join('services', 'booking_services.service_id', '=', 'services.id')
            ->select('bookings.*', 'booking_status.status as booking_status','users.first_name as provider_first_name','users.last_name as provider_last_name', 'users.profilepic as provider_profilepic', 'services.name as service_name', 'booking_services.initial_number_of_hours', 'booking_services.initial_service_cost')
            ->where('user_id', $user_id)
            ->where('booking_status_id', 2)
            ->where('booking_request_providers.status', 'accepted')
            ->get();
        // print_r($users);exit;

        return response()->json(['data' => $data]);

    }

    //for get cancelled booking details
    public function getcancelledbookingdetails(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        // print_r($user_id);exit;

        $data = DB::table('bookings')
            ->join('booking_status', 'bookings.booking_status_id', '=', 'booking_status.id')
            ->join('booking_request_providers', 'bookings.id', '=', 'booking_request_providers.booking_id')
			->join('users', 'booking_request_providers.provider_user_id', '=', 'users.id')
            ->join('booking_services', 'bookings.id', '=', 'booking_services.booking_id')
            ->join('services', 'booking_services.service_id', '=', 'services.id')
            ->select('bookings.*', 'booking_status.status as booking_status','users.first_name as provider_first_name','users.last_name as provider_last_name', 'users.profilepic as provider_profilepic', 'services.name as service_name', 'booking_services.initial_number_of_hours', 'booking_services.initial_service_cost')
            ->where('user_id', $user_id)
            ->where('booking_status_id', 5)
            ->where('booking_request_providers.status', 'accepted')
            ->get();
        // print_r($users);exit;

        return response()->json(['data' => $data]);

    }

    //for get rejected booking details
    public function getrejectedbookingdetails(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        // print_r($user_id);exit;

        $data = DB::table('bookings')
            ->join('booking_status', 'bookings.booking_status_id', '=', 'booking_status.id')
            ->join('booking_request_providers', 'bookings.id', '=', 'booking_request_providers.booking_id')
			->join('users', 'booking_request_providers.provider_user_id', '=', 'users.id')
            ->join('booking_services', 'bookings.id', '=', 'booking_services.booking_id')
            ->join('services', 'booking_services.service_id', '=', 'services.id')
            ->select('bookings.*', 'booking_status.status as booking_status','users.first_name as provider_first_name','users.last_name as provider_last_name', 'users.profilepic as provider_profilepic', 'services.name as service_name', 'booking_services.initial_number_of_hours', 'booking_services.initial_service_cost')
            ->where('user_id', $user_id)
            ->where('booking_status_id', 6)
            ->where('booking_request_providers.status', 'accepted')
            ->get();
        // print_r($users);exit;

        return response()->json(['data' => $data]);

    }

    //for provider cancel booking by uuid
    public function provider_cancelbooking(Request $request, $uuid)
    {
         $user = Auth::user();

        $user_id = $user->id;
        // print_r($user_id);exit;

        $data = DB::table('bookings')
            ->join('booking_status', 'bookings.booking_status_id', '=', 'booking_status.id')
            ->join('booking_services', 'bookings.id', '=', 'booking_services.booking_id')
            ->select('bookings.id as booking_id','bookings.booking_date','bookings.booking_time', 'booking_status.status as booking_status', 'booking_services.initial_number_of_hours as number_of_hours', 'booking_services.initial_service_cost as agreed_service_amount')
            ->where('bookings.uuid', $uuid)
            ->get();
        // print_r($data);exit;

        if($data[0]->booking_status != "cancelled"){
            // return response()->json(['message' => 'You cannot cancel booking! Please contact your provider.']);
            $booking_id = $data[0]->booking_id;
            $booking_date = $data[0]->booking_date;
            $booking_time = $data[0]->booking_time;
            $number_of_hours = $data[0]->number_of_hours;
            $agreed_service_amount = $data[0]->agreed_service_amount;

            $Bookingchange = Bookingchange::firstOrNew(['id' => $request->get('id')]);
            $Bookingchange->id = $request->get('id');
            $Bookingchange->uuid = $request->get('uuid');
            $Bookingchange->booking_id = $booking_id;
            $Bookingchange->is_cancelled = '1';
            $Bookingchange->booking_date = $booking_date;
            $Bookingchange->booking_time = $booking_time;
            $Bookingchange->number_of_hours = $number_of_hours;
            $Bookingchange->agreed_service_amount = $agreed_service_amount;
            $Bookingchange->comments = $request->get('comments');
            $Bookingchange->changed_by_user = $user_id;
            $Bookingchange->save();

            $lastinserteduuid = $Bookingchange->uuid;

            if($lastinserteduuid){
                $Booking = Booking::firstOrNew(['uuid' => $uuid]);
                $Booking->booking_status_id = '5';
                $Booking->save();

                $success['message'] = 'Booking cancelled successfully.';
                $success['cancelled_booking_uuid'] = $lastinserteduuid;

                return response()->json(['success' => $success]);
            } else{
                return response()->json(['message' => 'Failed to cancel this booking.']);
            }
        } else{
             return response()->json(['message' => 'Already cancelled this booking.']);
        }
    }
    
    //for cancel booking by uuid
    public function cancelbooking(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $bookingid = $request->id;
        // print_r($user_id);exit;

        $data = DB::table('bookings')
            ->join('booking_status', 'bookings.booking_status_id', '=', 'booking_status.id')
            ->join('booking_services', 'bookings.id', '=', 'booking_services.booking_id')
            ->select('bookings.id as booking_id','bookings.booking_date','bookings.booking_time', 'booking_status.status as booking_status', 'booking_services.initial_number_of_hours as number_of_hours', 'booking_services.initial_service_cost as agreed_service_amount')
            ->where('bookings.id', $bookingid)
            ->get();
        // print_r($data);exit;

        if($data[0]->booking_status != "pending"){
            return response()->json(['message' => 'You cannot cancel booking! Please contact your provider.']);
        } else{
            $booking_id = $data[0]->booking_id;
            $booking_date = $data[0]->booking_date;
            $booking_time = $data[0]->booking_time;
            $number_of_hours = $data[0]->number_of_hours;
            $agreed_service_amount = $data[0]->agreed_service_amount;

            $Bookingchange = Bookingchange::firstOrNew(['id' => $request->get('id')]);
            $Bookingchange->id = $request->get('id');
            //$Bookingchange->uuid = $request->get('uuid');
            $Bookingchange->booking_id = $booking_id;
            $Bookingchange->is_cancelled = '1';
            $Bookingchange->booking_date = $booking_date;
            $Bookingchange->booking_time = $booking_time;
            $Bookingchange->number_of_hours = $number_of_hours;
            $Bookingchange->agreed_service_amount = $agreed_service_amount;
            $Bookingchange->comments = $request->get('comments');
            $Bookingchange->changed_by_user = $user_id;
            $Bookingchange->save();

            $lastinserteduuid = $Bookingchange->uuid;

            if($lastinserteduuid){
                $Booking = Booking::firstOrNew(['id' => $bookingid]);
                $Booking->booking_status_id = '5';
                $Booking->save();

                $cancelbooking = app(BookingReqestProviderRepository::class)->CancelBooking($booking_id);
               
               // $success['message'] = 'Booking cancelled successfully.';
                $success['cancelled_booking_uuid'] = $lastinserteduuid;

                return response()->json(['success' => $success,'message' => 'Booking cancelled successfully.']);
            } else{
                return response()->json(['message' => 'Failed to cancel this booking.']);
            }
        }
    }

    // for customer edit booking
    public function customer_edit_booking(Request $request, $uuid){
        $validator = Validator::make($request->all(), [
            'booking_date' => 'required',
            'booking_time' => 'required'
        ]);

        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

        $user = Auth::user();
        $user_id = $user->id;
        // dd($user_id);

        $data = DB::table('bookings')
                ->where('user_id', $user_id)
                ->where('uuid', $uuid)
                ->where('booking_status_id', 1)
                ->get();

                $data = new Booking();
        $data = $data->where('user_id', $user_id)
                    ->where('uuid', $uuid)
                    ->where('booking_status_id', 1)->first();
        // $service_id = $service->id;
        // dd($data);

        if($data){
            $Booking = Booking::firstOrNew(['uuid' => $uuid]);
            $Booking->booking_date = $request->get('booking_date');
            $Booking->booking_time = $request->get('booking_time');
            $Booking->save();
            $lastinserteduuid = $Booking->uuid;

            $success['message'] = 'Booking edited successfully.';
            $success['edited_booking_uuid'] = $lastinserteduuid;

            return response()->json(['success' => $success]);
        }
        else{
            return response()->json(['message' => 'Unable to edit bcoz this booking is not in pending status.']);
        }
    }

    // for customer agency
    public function customer_add_agency(Request $request){
        $validator = Validator::make($request->all(), [
            'provider_type' => 'required'
        ]);

        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

        $user = Auth::user();
        $user_id = $user->id;
        // dd($user_id);
        $provider_type = $request->get('provider_type');

        $users = DB::table('users')
                ->where('providertype', $provider_type)
                ->get();

        $bookings = DB::table('bookings')
                ->where('booking_provider_type', $provider_type)
                ->get();

        if(isset($bookings) && isset($users)){
            foreach($bookings as $booking){
                $booking_id = $booking->id;  

                foreach($users as $user){
                    $user_id = $user->id;
    
                    $Bookingrequestprovider = Bookingrequestprovider::firstOrNew(['id' => $request->get('id')]);
                    $Bookingrequestprovider->booking_id = $booking_id;
                    $Bookingrequestprovider->provider_user_id = $user_id;
                    $Bookingrequestprovider->status = 'pending';
                    $Bookingrequestprovider->visible_to_enduser = 0;
                    $Bookingrequestprovider->save();
                }  
            }
            $responseCode = $request->get('id') ? 200 : 201;
            return response()->json(['saved' => true], $responseCode);
        } else{
            $responseCode = $request->get('id') ? 200 : 201;
            return response()->json(['saved' => false], $responseCode);
        }
    }

    // for change booking status
    public function change_booking_status(Request $request, $uuid){
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);

        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

        $user = Auth::user();
        $user_id = $user->id;
        // dd($user_id);
        
        $status = $request->get('status');

        $booking_status = DB::table('booking_status')
                ->where('status', $status)
                ->get();

        // dd($booking_status[0]->id);

        $booking_status_id = $booking_status[0]->id;

        if($booking_status_id){
            $Booking = Booking::firstOrNew(['uuid' => $uuid]);
            $Booking->booking_status_id = $booking_status_id;
            $Booking->save();
            $lastinserteduuid = $Booking->uuid;

            $success['message'] = 'Booking status changed successfully.';
            $success['edited_booking_uuid'] = $lastinserteduuid;

            return response()->json(['success' => $success]);
        }
        else{
            return response()->json(['message' => 'Unable to change the booking status.']);
        }
    }
}
