<?php
namespace App\Http\Controllers\Backend\API;

use App\Http\Controllers\Controller;
use App\Repository\UserBadgeReviewRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserBadgesController  extends Controller
{
    /**
     * @var UserBadgeReviewRepository
     */
    private $badgeRepository;

    /**
     * UserBadgesController constructor.
     * @param UserBadgeReviewRepository $badgeReviewRepository
     */
    public function __construct(UserBadgeReviewRepository $badgeReviewRepository)
    {
        $this->badgeRepository = $badgeReviewRepository;
    }

    public function index(Request $request)
    {
        $userId = $request->input('user_id') && session()->has('isAdmin') ? 
        $request->input('user_id'): Auth::id();
        $badges = $this->badgeRepository->getBadgeDetails($userId);

        return response()->json(['data'=> $badges],200);
    }

    public function saveBadge(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|integer',
            'badgeId' => 'required|integer',
        ]);

        $res = $this->badgeRepository->saveUserBadge($request);

        if($res){
            return response()->json(['data' => $res], 200);
        }else{
            return response()->json(['error' => true], 201);
        }
    }

    public function deleteBadge(Request $request)
    {
        $request->validate([
            'userBadgeId' => 'required|integer|exists:user_badges,id'
        ]);

        try {
            $response = $this->badgeRepository->deleteBadge($request);
        } catch (\Exception $exception) {
            $response = false;
        }

        if ($response) {
            return response()->json(['success' => true, 'message' => 'Badge deleted.'], 200);
        }

        return response()->json(['error' => true, 'message' => 'Unable to delete badge.'], 201);
    }

}