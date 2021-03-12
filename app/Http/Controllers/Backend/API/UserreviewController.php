<?php

namespace App\Http\Controllers\Backend\API;

use App\Userreview;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\UserreviewRequest;
use App\Http\Resources\UserreviewCollection;
use App\Http\Resources\Userreview as UserreviewResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Repository\UserBadgeReviewRepository;
use Auth;
use Hash;
use DB;

class UserreviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userreviews = Userreview::query();
        
		if ($request->has('user_review_for')) {
			$userreviews = $userreviews->where('user_review_for', 'LIKE', '%'.$request->get('user_review_for').'%');
		}
		if ($request->has('user_review_by')) {
			$userreviews = $userreviews->where('user_review_by', 'LIKE', '%'.$request->get('user_review_by').'%');
		}
		if ($request->has('booking_id')) {
			$userreviews = $userreviews->where('booking_id', 'LIKE', '%'.$request->get('booking_id').'%');
		}
		if ($request->has('rating')) {
			$userreviews = $userreviews->where('rating', 'LIKE', '%'.$request->get('rating').'%');
		}
		if ($request->has('comments')) {
			$userreviews = $userreviews->where('comments', 'LIKE', '%'.$request->get('comments').'%');
		}
		if ($request->has('published')) {
			$userreviews = $userreviews->where('published', 'LIKE', '%'.$request->get('published').'%');
		}
        $userreviews = $userreviews->paginate(20);
        return (new UserreviewCollection($userreviews));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(UserreviewRequest $request, Userreview $userreview)
    {
        $userreview = Userreview::firstOrNew(['id' => $request->get('id')]);
        $userreview->id = $request->get('id');
		$userreview->user_reviews_uuid = $request->get('user_reviews_uuid');
		$userreview->user_review_for = $request->get('user_review_for');
		$userreview->user_review_by = $request->get('user_review_by');
		$userreview->booking_id = $request->get('booking_id');
		$userreview->rating = $request->get('rating');
		$userreview->comments = $request->get('comments');
		$userreview->published = $request->get('published');

        $userreview->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }

    //for get user reviews data by uuid
    public function getuserreviewdata(Request $request, $uuid)
    {
        $user = Auth::user();
        $user_id = $user->id;
        // print_r($user_id);exit;

        $data = DB::table('booking_services')
            ->join('services', 'booking_services.service_id', '=', 'services.id')
            ->select('services.service_cost', 'services.name as service_name', 'booking_services.final_number_of_hours as duration', 'booking_services.booking_id', 'booking_services.completion_notes')
            ->where('booking_services.uuid', $uuid)
            ->get();
        // print_r($data);exit;

        return response()->json(['data' => $data]);
    }

    public function getcleanardata(Request $request,$uuid)
    {
      
            $users = DB::select("SELECT
                                  users.first_name,
                                  users.last_name,
                                  provider_service_maps.amount,
                                  users.profilepic,
                                  TRUNCATE(SUM(user_reviews.rating)*5/(COUNT(user_reviews.user_review_by)*5), 1) AS rating,
                                  comp.comp_total AS booking_complete
                                FROM users
                                  INNER JOIN user_reviews
                                    ON user_reviews.user_review_for = users.id              
                                  INNER JOIN provider_service_maps
                                    ON provider_service_maps.provider_id = users.id
                                  INNER JOIN completed_bookings as comp
                                    ON comp.user_id = users.id
                                WHERE users.uuid= '$uuid'
                                GROUP BY users.first_name,

                                  users.last_name,
                                  provider_service_maps.amount,
                                  users.profilepic,
                                  comp.comp_total 
                                ");

            $badges = DB::select("SELECT
                                  badges.badge_icon,
                                  badges.badge_label,
                                  badges.badge_description
                                FROM user_badges
                                  INNER JOIN badges
                                    ON user_badges.badge_id = badges.id
                                  INNER JOIN users
                                    ON user_badges.user_id = users.id
                                WHERE users.uuid= '$uuid' 
                                ");

            $reviews = DB::table('user_reviews')
            ->select('user_reviews.rating','user_reviews.comments')
            ->join('users', 'user_reviews.user_review_for', '=', 'users.id')
            ->where('users.uuid', $uuid)
            ->orderby('user_reviews.created_at','ASC')
            ->get();


        return response()->json(['users' =>$users,'total_reviews'=>count($reviews),'badges'=>$badges,'review_and_rating'=>$reviews]);
    }
    //for add provider review
    public function addreview(Request $request, $id)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $validator = Validator::make($request->all(), [
            'ratings' => 'required|numeric',
            'review_for' => 'required|numeric',
        ]);

        if($validator->fails()) {
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

        $response = app(UserBadgeReviewRepository::class)->addreview($request,$id);

        if($response){
            return response()->json(['saved' => true], 200);
        }
       
    }

    //for get rating review data
    public function getratingreview(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        // print_r($user_id);exit;

        $data = DB::table('userreviews')
            ->join('booking_services', 'userreviews.booking_id', '=', 'booking_services.booking_id')
            ->join('services', 'booking_services.service_id', '=', 'services.id')
            ->select('userreviews.created_at as placed_on', 'userreviews.rating', 'services.name as service_name')
            ->where('userreviews.user_review_by', $user_id)
            ->get();
        // print_r($data);exit;

        return response()->json(['data' => $data]);
    }

    //for get users cancelled booking data
    public function getcancelbookingdata(Request $request, $uuid)
    {
        $data = DB::table('booking_changes')
            ->join('users', 'booking_changes.changed_by_user', '=', 'users.id')
            ->select('booking_changes.booking_datetime', 'users.first_name', 'users.last_name', 'users.profilepic')
            ->where('booking_changes.uuid', $uuid)
            ->get();
        // print_r($data);exit;

        return response()->json(['data' => $data]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $userreview = Userreview::find($request->get('id'));
        $userreview->delete();
        return response()->json(['no_content' => true], 200);
    }

    /**
     * Restore the specified resource to storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        $userreview = Userreview::withTrashed()->find($request->get('id'));
        $userreview->restore();
        return response()->json(['no_content' => true], 200);
    }
}
