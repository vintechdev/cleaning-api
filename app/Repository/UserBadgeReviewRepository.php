<?php 
namespace App\Repository;
use App\Providerservicemaps;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Userreview;
use App\UserBadge;
use App\Badges;
use Illuminate\Http\Request;
use config;
class UserBadgeReviewRepository{

    public function getBadgeDetails($providerid){
        $badges = UserBadge::leftJoin('badges', function($join){
                    $join->on('user_badges.badge_id', '=', 'badges.id');
                })->where('user_badges.user_id',$providerid)
                ->get()->toArray();
                return $badges;
    }

    public function getReviewDetails($providerid){
        $review = Userreview::leftJoin('users', function($join){
                    $join->on('user_reviews.user_review_by', '=', 'users.id');
                })->where('user_reviews.user_review_for',$providerid)->get()->toArray();
                    return $review;
    }
    public function getAvgRating($providerid)
    {
       # code...
       $avrate = Userreview::where('user_reviews.user_review_for',$providerid)->avg('rating');
       return $avrate;

    }

    public function addreview(Request $request,$id)
    {
        # code...
        $r = new Userreview;
       
        $r->booking_id = $id;
        $r->user_review_for  = $request->review_for;
        $r->rating  = $request->ratings;
        $r->user_review_by  = Auth::user()->id;
        if($request->has('comments')){
            $r->comments = $request->comments;
        }
        $r->review_type = $request->type;
        $r->published =1;
        $r->save();
        return true;
    }
    public function getreviewbybooking($id)
    {
        # code...
        $rev = [];
        $user = Auth::user();
        if(in_array('provider', $user->getScopes())){
            $type='provider';
        }else{
            $type = 'user';
        }
        $arr = Userreview::with('reviewby','reviewfor')->where('booking_id',$id)->where('published',1)->get()->toArray();
       if(count($arr)>0){
        
          $review =[];
           foreach($arr as $k=>$v){
                $review['booking_id'] = $v['booking_id'];
                $review['user_review_for'] = $v['user_review_for'];
                $review['user_review_by'] = $v['user_review_by'];
                $review['review_for_name'] = $v['reviewfor']['first_name'].' '.$v['reviewfor']['last_name'];
                $review['review_by_name'] = $v['reviewby']['first_name'].' '.$v['reviewby']['last_name'];
                $review['review_type'] = $v['review_type'];
                $review['comments'] = $v['comments'];
                $review['rating'] = $v['rating'];
                $review['created_at'] = $v['created_at'];
                $rev[] = $review;
           }
       }
       return $rev;

    }
    

}


?>