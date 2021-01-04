<?php

namespace App\Http\Controllers\Backend\API;

use App\Booking;
use App\Services\Bookings\BookingJobsManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Bookingstatus;

/**
 * Class BookingJobsController
 * @package App\Http\Controllers\Backend\API
 */
class BookingJobsController extends Controller
{
    /**
     * @param Request $request
     * @param BookingJobsManager $bookingManager
     * @return \Illuminate\Http\JsonResponse
     */
    public function listAllJobs(Request $request, BookingJobsManager $bookingManager)
    {

     
        $validator = Validator::make(
            $request->all(),
            [
                'from' => 'date_format:d-m-Y H:i:s',
                'total_period' => 'int',
                'type' => [
                    Rule::in(['all', 'past', 'future'])
                ]
            ]
        );

        if ($validator->fails()) {
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 400);
        }

        $from = $request->has('from') ? Carbon::createFromFormat('d-m-Y H:i:s', $request->get('from')) : null;
        $totalDays = $request->has('total_period') ? $request->get('total_period') : 30;

        if (!$request->has('type') || $request->get('type') == 'all') {
            $jobs = $bookingManager->getAllBookingJobsByUser(auth()->user(), $from, $totalDays);
            return response()->json($jobs, 200);
        }

        if ($request->get('type') == 'past') {
            $jobs = $bookingManager->getAllPastBookingJobsByUser(auth()->user(), $from, $totalDays);
            return response()->json($jobs, 200);
        }

        $jobs = $bookingManager->getAllFutureBookingJobsByUser(auth()->user(), $from, $totalDays);
        return response()->json($jobs, 200);
    }
    //list all provider's job request
    public function listAllProviderJobs(Request $request, BookingJobsManager $bookingManager)
    {

     
        $validator = Validator::make(
            $request->all(),
            [
                'from' => 'date_format:d-m-Y H:i:s',
                'total_period' => 'int',
                'type' => [
                    Rule::in(['all', 'past', 'future'])
                ]
            ]
        );

        if ($validator->fails()) {
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 400);
        }

        $from = $request->has('from') ? Carbon::createFromFormat('d-m-Y H:i:s', $request->get('from')) : null;
        $totalDays = $request->has('total_period') ? $request->get('total_period') : 30;

        if (!$request->has('type') || $request->get('type') == 'all') {
            $jobs = $bookingManager->getAllBookingJobsByUser(auth()->user(), $from, $totalDays,true);
            return response()->json($jobs, 200);
        }

        if ($request->get('type') == 'past') {
            $jobs = $bookingManager->getAllPastBookingJobsByUser(auth()->user(), $from, $totalDays,true);
            return response()->json($jobs, 200);
        }

        $jobs = $bookingManager->getAllFutureBookingJobsByUser(auth()->user(), $from, $totalDays,true);
        return response()->json($jobs, 200);
    }

    public function bookingdetails(Request $request,BookingJobsManager $bookingManager ){
      
       
        $id = $request->id;
        $details = $bookingManager->getBookingDetailsByProvider(auth()->user(),$id);
        $status = Bookingstatus::all()->toArray();
        return response()->json(['details'=>$details,'statusRecords'=>$status], 200);

    }
}