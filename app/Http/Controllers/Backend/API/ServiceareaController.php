<?php

namespace App\Http\Controllers\Backend\API;

use App\Servicearea;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\ServiceareaRequest;
use App\Http\Resources\ServiceareaCollection;
use App\Http\Resources\Servicearea as ServiceareaResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use Hash;

class ServiceareaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $servicesarea = Servicearea::query()->where('provider_id', $user_id);
        $servicesarea = $servicesarea->paginate(20);
        return response()->json(['data' => $servicesarea]);
        //return (new ServiceareaCollection($servicesarea));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    

    //for add services
    public function addservicesarea(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'postcode_id' => 'required'
           
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }
        $user = Auth::user();
        $user_id = $user->id;
        $servicearea = Servicearea::firstOrNew(['id' => $request->get('id')]);
        $servicearea->id = $request->get('id');
		$servicearea->uuid = $request->get('uuid');
        $servicearea->provider_id = $user_id;
        $servicearea->postcode_id = $request->get('postcode_id');
        $servicearea->save();
        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }
   

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   
      public function deleteservicearea(Request $request,$uuid)
    {

        $skills = Servicearea::where('uuid',$uuid)->orWhere('id',$uuid)->first();
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
        $service = Service::withTrashed()->find($request->get('id'));
        $service->restore();
        return response()->json(['no_content' => true], 200);
    }
}
