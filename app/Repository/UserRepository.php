<?php

namespace App\Repository;

use App\Providerservicemaps;
use App\Promocodes;
use App\User;
use App\Customeruser;
use App\UserNotification;
use App\Bookingrequestprovider;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UserRepository
{

    public function getProviderDetails($id)
    {
        # code...
        $res = User::where('id', $id)->get()->toArray();
        return $res;
    }

    public function getUserDetails($id)
    {
        # code...
        $res = User::where('id', $id)->get()->toArray();
        return $res;
    }

    public function GetServicePriceofProvider($serviceid, $providerid)
    {

        $pdr = Providerservicemaps::leftJoin('services', function ($join) {
            $join->on('services.id', '=', 'provider_service_maps.service_id');
        })->whereIn('provider_service_maps.service_id', $serviceid)
            ->where('provider_service_maps.provider_id', $providerid)->get(['services.service_type', 'services.is_default_service', 'provider_service_maps.*'])->toarray();
        return $pdr;
    }

    public function CheckPromocode($promocode, $categoryid)
    {
        $res = Promocodes::where('name', $promocode)->where('category_id', $categoryid)->limit(1)->get()->toArray();
        return $res;
    }

    public function getAgencyData()
    {
        # code...
        $users = Customeruser::where('providertype', 'agency')->pluck('id')->toArray();
        return $users;
    }

    public function getUserNotification($user_id, $nid)
    {

        $notification = UserNotification::where('user_id', $user_id)->where('notification_id', $nid)->first()->toarray();
        return $notification;
    }

    //get completed booking statitics
    public function getdashboardstatistics()
    {
        $query = Bookingrequestprovider::join('bookings', 'booking_id', '=', 'bookings.id');

        $query1 = clone $query;
        $query2 = clone $query;
        $cost = $query->where('bookings.booking_status_id', 4)->sum('bookings.final_cost');
        $totalcompletedbooking = $query1->where('bookings.booking_status_id', 4)->count();

        //total customer
        $totalcustomer = $query2->where('bookings.booking_status_id', '!=', 1)->where('bookings.booking_status_id', '!=', 6)->distinct('bookings.user_id')->count('bookings.user_id');


        return ['total_amount' => $cost, 'completedbooking' => $totalcompletedbooking, 'totalcustomer' => $totalcustomer];
    }

    public function getNewUsers($filters = [])
    {
        $fromDate = Arr::get($filters, 'from') ?
            Carbon::parse(Arr::get($filters, 'from'))->format('Y-m-d 00:00:00'):
            Carbon::now()->firstOfMonth()->format('Y-m-d 00:00:00');

        return User::query()->newQuery()
            ->whereExists(function ($query) use ($filters) {
               $query->select("role_user.user_id")
                   ->from("role_user")
                   ->join("roles", "roles.id", "=", "role_user.role_id")
                   ->where("roles.name", "=", DB::raw("'" . $filters['role']."'"))
                   ->whereRaw("role_user.user_id = users.id");
            })
            ->where('created_at', '>=', $fromDate)->get();
    }
}