<?php

namespace App\Http\Controllers\Backend\API;

use App\Plan;
use App\Servicecategory;
use App\Services\PlansService;
use Illuminate\Http\Request;
// use Shahnewaz\Redprint\Traits\CanUpload;
use App\Http\Requests\Backend\PlanRequest;
use App\Http\Resources\PlanCollection;
use App\Http\Resources\Plan as PlanResource;
use App\Http\Controllers\Controller;

class PlansController extends Controller
{
    // use CanUpload;
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param PlansService $plansService
     * @return \Illuminate\Http\Response
     */
    public function get_all_plan(Request $request, PlansService $plansService)
    {
        $plans = $plansService->getAllPlansDetails($request->has('service_category_id') ? Servicecategory::find($request->get('service_category_id')) : null);
        return json_encode(['data'=>$plans]);
    }

    public function get(Request $request,$users_uuid,$plans_uuid)
    {
        $plans = Plan::query();

        if ($plans_uuid) {
            $plans = $plans->where('id',$plans_uuid)->orWhere('uuid',$plans_uuid);
        }
        
		if ($request->has('plan_name')) {
			$plans = $plans->where('plan_name', 'LIKE', '%'.$request->get('plan_name').'%');
		}
		if ($request->has('features')) {
			$plans = $plans->where('features', 'LIKE', '%'.$request->get('features').'%');
		}
		if ($request->has('discount')) {
			$plans = $plans->where('discount', 'LIKE', '%'.$request->get('discount').'%');
		}
        $plans = $plans->paginate(20);
        return (new PlanCollection($plans));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_plan(PlanRequest $request,$users_uuid)
    {
        $plan = Plan::firstOrNew(['id' => $request->get('users_uuid')]);
		$plan->plan_name = $request->get('plan_name');
		$plan->features = $request->get('features');
		$plan->discount = $request->get('discount');

        $plan->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }

    public function edit_plan(PlanRequest $request,$users_uuid,$plans_uuid)
    {
        $plan = Plan::firstOrNew(['uuid' => $plans_uuid]);
        $plan->plan_name = $request->get('plan_name');
        $plan->features = $request->get('features');
        $plan->discount = $request->get('discount');

        $plan->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_plan($users_uuid,$plans_uuid)
    {
        $plan =Plan::where('uuid',$plans_uuid)->orWhere('id',$plans_uuid)->first();
        if ($plan != null) {
            $plan->delete();
            return response()->json(['no_content' => $plan], 200);
        }
        else{
            return response()->json(['no_content' => 'Wrong ID!!']);
        }

    }

    /**
     * Restore the specified resource to storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function restore(Request $request)
    // {
    //     $plan = Plan::withTrashed()->find($request->get('id'));
    //     $plan->restore();
    //     return response()->json(['no_content' => true], 200);
    // }
}
