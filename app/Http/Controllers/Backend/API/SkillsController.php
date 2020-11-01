<?php

namespace App\Http\Controllers\Backend\API;

use App\Skills;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\SkillsRequest;
use App\Http\Resources\SkillsCollection;
use App\Http\Resources\Skills as SkillsResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use Hash;
use DB;

class SkillsController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $skills = Skills::query();
        
		if ($request->has('user_id')) {
			$skills = $skills->where('user_id', 'LIKE', '%'.$request->get('user_id').'%');
		}
		if ($request->has('skill_name')) {
			$skills = $skills->where('skills', 'LIKE', '%'.$request->get('skills').'%');
		}
		
		
        $skills = $skills->paginate(20);
        return (new SkillsCollection($skills));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(SkillsRequest $request, Skills $skills)
    {
       
        $skills = Working_hours::firstOrNew(['id' => $request->get('id')]);
        $skills->id = $request->get('id');
		$skills->uuid = $request->get('uuid');
		$skills->user_id = $request->get('user_id');
		$skills->skill_name = $request->get('skill_name');
		
		

        $skills->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }

    //for add address
    public function addskills(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'skill_name' => 'required'
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

       

        $user = Auth::user();
        $user_id = $user->id;
      
            $skills = Skills::firstOrNew(['id' => $request->get('id')]);
            $skills->id = $request->get('id');
            $skills->uuid = $request->get('uuid');
            $skills->user_id = $user_id;
            $skills->skill_name = $request->get('skill_name');
            $skills->save();

            $responseCode = $request->get('id') ? 200 : 201;
            return response()->json(['saved' => true], $responseCode);
        
    }

    //for update address by uuid
    public function editskills(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            // 'type' => 'required',
            
            'skill_name' => 'required'
           
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

    

        $user = Auth::user();
        $user_id = $user->id;
       
            $skills = Skills::firstOrNew(['uuid' => $uuid]);
            // $Useraddress->type = $request->get('type');
            $skills->skill_name = $request->get('skill_name');
            
            $skills->save();
            
            $responseCode = $request->get('id') ? 200 : 201;
            return response()->json(['saved' => $skills], $responseCode);
        
    }

    //for get address
    public function getskills(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        // print_r($user_id);exit;

        $skills = Skills::query();
        
		if ($user_id) {
			$skills = $skills->where('user_id', 'LIKE', '%'.$user_id);
		}
		
        $skills = $skills->paginate(20);
        return (new SkillsCollection($skills));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $skills = Skills::find($request->get('id'));
        $skills->delete();
        return response()->json(['no_content' => true], 200);
    }
    public function deleteskills(Request $request,$uuid)
    {

        $skills = Skills::where('uuid',$uuid)->orWhere('id',$uuid)->first();
        if ($skills != null) {
            $skills->delete();
            return response()->json(['no_content' => $skills], 200);
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
    public function restore(Request $request)
    {
        $skills = Skills::withTrashed()->find($request->get('id'));
        $skills->restore();
        return response()->json(['no_content' => true], 200);
    }
}
