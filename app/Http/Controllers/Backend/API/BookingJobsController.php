<?php

namespace App\Http\Controllers\Backend\API;

use App\Booking;
use App\Services\Bookings\BookingManager;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Class BookingJobsController
 * @package App\Http\Controllers\Backend\API
 */
class BookingJobsController extends Controller
{
    /**
     * @param Request $request
     * @param BookingManager $bookingManager
     * @return \Illuminate\Http\JsonResponse
     */
    public function listAllJobs(Request $request, BookingManager $bookingManager)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'offset' => 'int',
                'limit' => 'int',
                'type' => [
                    Rule::in(['all', 'past', 'future'])
                ]
            ]
        );

        if ($validator->fails()) {
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 400);
        }

        if (!$request->has('type') || $request->get('type') == 'all') {
            $jobs = $bookingManager->getAllBookingJobsByUser(auth()->user());
            return response()->json($jobs, 200);
        }

        if ($request->get('type') == 'past') {
            $jobs = $bookingManager->getAllPastBookingJobsByUser(auth()->user());
            return response()->json($jobs, 200);
        }

        $jobs = $bookingManager->getAllFutureBookingJobsByUser(auth()->user());
        return response()->json($jobs, 200);
    }
}