<?php
namespace App\Http\Controllers\Backend\API;


use App\Http\Controllers\Controller;
use App\Services\Bookings\BookingActivityLogger;
use Illuminate\Http\Request;

class BookingActivityLogsController extends Controller
{
    /**
     * @var BookingActivityLogger
     */
    private $bookingActivityLogger;

    /**
     * BookingActivityLogsController constructor.
     * @param BookingActivityLogger $bookingActivityLogger
     */
    public function __construct(BookingActivityLogger $bookingActivityLogger)
    {
        $this->bookingActivityLogger = $bookingActivityLogger;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $logs = $this->bookingActivityLogger->getAll($request->all());
        return response()->json(['success'=> true, 'data' => $logs], 200);
    }
}