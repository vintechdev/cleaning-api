<?php

namespace App\Http\Controllers\Backend\API;

use App\Providerportfolios;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\ProviderportfoliosRequest;
use App\Http\Resources\ProviderportfoliosCollection;
use App\Http\Resources\Providerportfolios as ProviderportfoliosResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use Hash;
use DB;

class ProviderportfoliosController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $providerportfolios = Providerportfolios::query();
        
		if ($request->has('user_id')) {
			$providerportfolios = $providerportfolios->where('user_id', 'LIKE', '%'.$request->get('user_id').'%');
		}
		if ($request->has('image')) {
			$providerportfolios = $providerportfolios->where('image', 'LIKE', '%'.$request->get('image').'%');
		}
		
		
        $providerportfolios = $skills->paginate(20);
        return (new ProviderportfoliosCollection($providerportfolios));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    

    //for add address
    public function addportfolios(Request $request)
    {
       
              $validator = Validator::make($request->all(), [
            'image' => 'required'
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

       

        $user = Auth::user();
        $user_id = $user->id;
      
            $providerportfolios = Providerportfolios::firstOrNew(['id' => $request->get('id')]);
            $providerportfolios->id = $request->get('id');
            $providerportfolios->uuid = $request->get('uuid');
            $providerportfolios->user_id = $user_id;
            $providerportfolios->image = $request->get('image');
            $providerportfolios->save();

            $responseCode = $request->get('id') ? 200 : 201;
            return response()->json(['saved' => true], $responseCode);
        
    }

    //for update address by uuid
    

    //for get address
    public function getportfolios(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        // print_r($user_id);exit;

        $providerportfolios = Providerportfolios::query();
        
		if ($user_id) {
			$providerportfolios = $providerportfolios->where('user_id', 'LIKE', '%'.$user_id);
		}
		
        $providerportfolios = $providerportfolios->paginate(20);
        return (new ProviderportfoliosCollection($providerportfolios));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $providerportfolios = Providerportfolios::find($request->get('id'));
        $providerportfolios->delete();
        return response()->json(['no_content' => true], 200);
    }
    public function deleteportfolios(Request $request,$uuid)
    {

        $providerportfolios = Providerportfolios::where('uuid',$uuid)->orWhere('id',$uuid)->first();
        if ($providerportfolios != null) {
            $providerportfolios->delete();
            return response()->json(['no_content' => $providerportfolios], 200);
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
        $providerportfolios = Providerportfolios::withTrashed()->find($request->get('id'));
        $providerportfolios->restore();
        return response()->json(['no_content' => true], 200);
    }
}
