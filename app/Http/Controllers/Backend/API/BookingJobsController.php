<?php

namespace App\Http\Controllers\Backend\API;

use App\Booking;
use App\Services\Bookings\BookingJobsManager;
use Carbon\Carbon;
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
     * @param BookingJobsManager $bookingManager
     * @return \Illuminate\Http\JsonResponse
     */
    public function listAllJobs(Request $request, BookingJobsManager $bookingManager)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'from' => 'date_format:d-m-Y H:i:s',
                'total_days' => 'int',
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
        $totalDays = $request->has('total_days') ? $request->get('total_days') : 30;

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
}