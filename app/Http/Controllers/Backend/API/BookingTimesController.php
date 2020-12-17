<?php

namespace App\Http\Controllers\Backend\API;

use App\Booking;
use App\Services\BookingEventService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

/**
 * Class BookingTimesController
 * @package App\Http\Controllers\Backend\API
 */
class BookingTimesController extends Controller
{
    /**
     * @param Request $request
     * @param BookingEventService $bookingEventService
     * @return \Illuminate\Http\JsonResponse
     */
    public function listBookingTimesByBookingId(Request $request, int $bookingId, BookingEventService $bookingEventService)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'from' => 'string',
                'limit' => 'int',
                'offset' => 'int'
            ]
        );

        if ($validator->fails()) {
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 400);
        }

        /** @var Booking $booking */
        $booking = Booking::find($bookingId);

        //TODO: Allow admin to fetch the times
        if ($booking->user_id != auth()->user()->id) {
            return response()->json(['message' => 'You are not allowed to access this resource'], 403);
        }

        $limit = $request->has('limit') ? $request->get('limit') : 10;
        $offset = $request->has('offset') ? $request->get('offset') : 1;

        $dates = $bookingEventService
            ->listBookingDates(
                $booking,
                $request->has('from') ?
                    Carbon::createFromFormat('d-m-Y H:i:s', $request->get('from')) :
                    null,
                $limit,
                $offset
            );

        $response = [
            'times' => []
        ];

        if ($dates) {
            $response['times'] = $dates;

            if (isset($dates[$limit - 1])) {
                $response['next_url'] = action('Backend\API\BookingTimesController@listBookingTimesByBookingId', ['bookingId' => $bookingId]) .
                    '?limit=' . $limit . '&offset=' . ($offset + $limit);
            }

            $response['previous_url'] = action('Backend\API\BookingTimesController@listBookingTimesByBookingId', ['bookingId' => $bookingId]) .
                '?limit=' . $limit . '&offset=' . ($offset - $limit);
        }

        return response()->json($response, 200);
    }
}