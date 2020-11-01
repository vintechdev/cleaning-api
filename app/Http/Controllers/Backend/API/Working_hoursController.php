<?php

namespace App\Http\Controllers\Backend\API;

use App\Working_hours;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\Working_hoursRequest;
use App\Http\Resources\Working_hoursCollection;
use App\Http\Resources\Working_hours as Working_hoursResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use Hash;
use DB;

class Working_hoursController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $working_hours = Working_hours::query();
        
		if ($request->has('provider_id')) {
			$working_hours = $working_hours->where('provider_id', 'LIKE', '%'.$request->get('provider_id').'%');
		}
		if ($request->has('working_days')) {
			$working_hours = $working_hours->where('working_days', 'LIKE', '%'.$request->get('working_days').'%');
		}
		if ($request->has('start_time')) {
			$working_hours = $working_hours->where('start_time', 'LIKE', '%'.$request->get('start_time').'%');
		}
		if ($request->has('end_time')) {
			$working_hours = $working_hours->where('end_time', 'LIKE', '%'.$request->get('end_time').'%');
		}
		
        $working_hours = $working_hours->paginate(20);
        return (new Working_hoursCollection($working_hours));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(Working_hoursRequest $request, Working_hours $working_hours)
    {
       
        $working_hours = Working_hours::firstOrNew(['id' => $request->get('id')]);
        $working_hours->id = $request->get('id');
		$working_hours->uuid = $request->get('uuid');
		$working_hours->provider_id = $request->get('provider_id');
		$working_hours->working_days = $request->get('working_days');
		$working_hours->start_time = $request->get('start_time');
		$working_hours->end_time = $request->get('end_time');
		

        $working_hours->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }

    //for add address
    public function addworking_hours(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'provider_id' => 'required',
            'working_days' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

       

        $user = Auth::user();
        $user_id = $user->id;
      
            $Working_hours = Working_hours::firstOrNew(['id' => $request->get('id')]);
            $Working_hours->id = $request->get('id');
            $Working_hours->uuid = $request->get('uuid');
            $Working_hours->provider_id = $user_id;
            $Working_hours->working_days = $request->get('working_days');
            $Working_hours->start_time = $request->get('start_time');
            $Working_hours->end_time = $request->get('end_time');
            $Working_hours->save();

            $responseCode = $request->get('id') ? 200 : 201;
            return response()->json(['saved' => true], $responseCode);
        
    }

    //for update address by uuid
    public function editworking_hours(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            // 'type' => 'required',
            
            'working_days' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

    

        $user = Auth::user();
        $user_id = $user->id;
       
            $Working_hours = Working_hours::firstOrNew(['uuid' => $uuid]);
            // $Useraddress->type = $request->get('type');
            $Working_hours->working_days = $request->get('working_days');
            $Working_hours->start_time = $request->get('start_time');
            $Working_hours->end_time = $request->get('end_time');
            $Working_hours->save();
            
            $responseCode = $request->get('id') ? 200 : 201;
            return response()->json(['saved' => $Working_hours], $responseCode);
        
    }

    //for get address
    public function getworking_hours(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        // print_r($user_id);exit;

        $working_hours = Working_hours::query();
        
		if ($user_id) {
			$working_hours = $working_hours->where('provider_id', 'LIKE', '%'.$user_id);
		}
		
        $working_hours = $working_hours->paginate(20);
        return (new Working_hoursCollection($working_hours));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $working_hours = Working_hours::find($request->get('id'));
        $working_hours->delete();
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
        $working_hours = Working_hours::withTrashed()->find($request->get('id'));
        $working_hours->restore();
        return response()->json(['no_content' => true], 200);
    }
}
