<?php
namespace App\Http\Controllers\Backend\API;

use App\Repository\UserRepository;
use App\Services\AdminSummaryManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;

class AdminSummaryController extends Controller
{
    /**
     * @var AdminSummaryManager
     */
    private $adminSummaryManager;

    /**
     * @var UserRepository
     */
    private $userRepository;


    /**
     * AdminSummaryController constructor.
     * @param AdminSummaryManager $adminSummaryManager
     * @param UserRepository $userRepository
     */
    public function __construct(AdminSummaryManager $adminSummaryManager, UserRepository $userRepository)
    {
        $this->adminSummaryManager = $adminSummaryManager;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNewBookings(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = collect([]);
        if (Arr::get($request->all(), 'type', 'count') === 'data') {
            $data = $this->adminSummaryManager->getNewBookings($request->all());
            $count = $data->count();
        } else {
            $count = $this->adminSummaryManager->getNewBookingCount($request->all());
        }

        return response()->json(['data', $data, 'count' => $count], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNewUsers(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = collect([]);

        $filters = $request->all();
        $filters['role'] = 'customer';
        if (Arr::get($request->all(), 'type', 'count') === 'data') {
            $data = $this->adminSummaryManager->getNewUsers($filters);
            $count = $data->count();
        } else {
            $count = $this->adminSummaryManager->getNewUserCount($filters);
        }

        return response()->json(['data' => $data, 'count' => $count], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNewProviders(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = collect([]);

        $filters = $request->all();
        $filters['role'] = 'provider';
        if (Arr::get($request->all(), 'type', 'count') === 'data') {
            $data = $this->adminSummaryManager->getNewUsers($filters);
            $count = $data->count();
        } else {
            $count = $this->adminSummaryManager->getNewUserCount($filters);
        }

        return response()->json(['data'=> $data, 'count' => $count], 200);
    }
    
}
