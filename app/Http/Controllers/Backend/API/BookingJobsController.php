<?php

namespace App\Http\Controllers\Backend\API;

use App\Services\Bookings\BookingJobsManager;
use App\User;
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
                'from' => 'date_format:dmYHis',
                'to' => 'date_format:dmYHis',
                'status' => [
                    Rule::in(array_values(Bookingstatus::getAllStatusNames()))
                ]
            ]
        );

        if ($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 400);
        }

        /** @var User $user */
        $user = auth()->user();

        try {
            $jobs = $bookingManager->getBookingJobsByStatus(
                Bookingstatus::getStatusIdByName($request->get('status')),
                $user,
                in_array('provider', $user->getScopes()),
                $request->get('from') ? Carbon::createFromFormat('dmYHis', $request->get('from')) : null,
                $request->get('to') ? Carbon::createFromFormat('dmYHis', $request->get('to')) : null
            );
        } catch (\InvalidArgumentException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        } catch (\Exception $exception) {
            throw $exception;
            return response()->json(['message' => 'Something went wrong. Please contact administrator.'], 500);
        }

        return response()->json($jobs, 200);
    }
}