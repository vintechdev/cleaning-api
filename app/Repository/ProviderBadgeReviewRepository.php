<?php 
namespace App\Repository;
use App\Providerservicemaps;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Userreview;
use App\UserBadge;
use App\Badges;
class ProviderBadgeReviewRepository{

    public function getBadgeDetails($providerid){
        $badges = UserBadge::leftJoin('badges', function($join){
                    $join->on('user_badges.badge_id', '=', 'badges.id');
                })->where('user_badges.user_id',$providerid)->get()->toArray();
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

}


?>