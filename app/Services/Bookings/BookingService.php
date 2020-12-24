<?php

namespace App\Services\Bookings;

use App\Booking;
use App\Bookingaddress;
use App\Bookingquestion;
use App\Bookingrequestprovider;
use App\Bookingstatus;
use App\Events\BookingCreated;
use App\Exceptions\Booking\BookingCreationException;
use App\Exceptions\NoSavedCardException;
use App\Plan;
use App\Repository\BookingReqestProviderRepository;
use App\Repository\Eloquent\StripeUserMetadataRepository;
use App\User;
use App\Useraddress;
use Illuminate\Support\Facades\DB;

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
     * BookingService constructor.
     * @param StripeUserMetadataRepository $stripeUserMetadataRepository
     * @param BookingReqestProviderRepository $reqestProviderRepository
     */
    public function __construct(
        StripeUserMetadataRepository $stripeUserMetadataRepository,
        BookingReqestProviderRepository $reqestProviderRepository
    )
    {
        $this->stripeUserMetadataRepository = $stripeUserMetadataRepository;
        $this->requestProviderRepo = $reqestProviderRepository;
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

        if(count($bookings)>0) {
            DB::beginTransaction();
            try {
                $booking = new Booking();

                $booking->user_id = $user->getId();
                try {
                    $booking->setStatus(isset($bookingAttributes['booking_status']) ?: Bookingstatus::BOOKING_STATUS_PENDING);
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
                        $bookingaddress->subrub = $address['subrub'];
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

                    //TODO: Validate if they service belongs to the category chosen
                    if (count($service) > 0) {
                        foreach ($service as $key => $serv) {
                            $bookingservice = new \App\Bookingservice;
                            $bookingservice->booking_id = $last_insert_id;
                            $bookingservice->service_id = $serv['service_id'];
                            $bookingservice->initial_number_of_hours = $serv['initial_number_of_hours'];
                            $bookingservice->initial_service_cost = $serv['initial_service_cost'];
                            $bookingservice->final_number_of_hours = $serv['final_number_of_hours'];
                            $bookingservice->final_service_cost = $serv['final_service_cost'];
                            if (!$bookingservice->save()) {
                                DB::rollBack();
                                throw new BookingCreationException('Booking service could not be saved');
                            }
                        }
                    }

                    if (!empty($question)) {
                        foreach ($question as $key => $quest) {
                            if ($quest['answer'] != null) {
                                $bookingquestion = new Bookingquestion();
                                $bookingquestion->booking_id = $last_insert_id;
                                $bookingquestion->service_question_id = $quest['service_question_id'];
                                $bookingquestion->answer = $quest['answer'];
                                if (!$bookingquestion->save()) {
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
        $services = $booking->getBookingServices()->toArray();
        $providers = $this->requestProviderRepo->getAllByBookingId($booking->getId())->toArray();
        $questions = Bookingquestion::where(['booking_id' => $booking->getId()])->get()->toArray();
    
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
}