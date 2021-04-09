<?php

namespace App\Http\Controllers\Backend\API;

use App\Postcode;
use Illuminate\Http\Request;
//use Shahnewaz\Redprint\Traits\CanUpload;
use App\Http\Requests\Backend\PostcodeRequest;
use App\Http\Resources\PostcodeCollection;
use App\Http\Resources\Postcode as PostcodeResource;
use App\Http\Controllers\Controller;
use App\Repository\Eloquent\ProfileRepository;
use DB;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Validator;
class PostcodesController extends Controller
{


public function addproviderpostcode(Request $request)
{
    $validator = Validator::make($request->all(), [
        'postcode' => 'required|numeric',
    ]);
    
    if($validator->fails()){
        $message = $validator->messages()->all();
        return response()->json(['message' => $message], 401);
    }

    $postcode = $request->postcode;
    $res = app(ProfileRepository::class)->addproviderpostcode($postcode);
    
    if($res){
        return response()->json(['data' => $res], 200);
    }else{
        return response()->json(['error' => true], 201); 
    }

}
public function getproviderpostcode(Request $request)
{
    $res = app(ProfileRepository::class)->getproviderpostcode($request);
    return response()->json(['data' => $res], 200);
   
}
public function deleteproviderpostcode(Request $request)
{
    $validator = Validator::make($request->all(), [
        'postcode' => 'required|numeric',
    ]);
    
    if($validator->fails()){
        $message = $validator->messages()->all();
        return response()->json(['message' => $message], 401);
    }
    $postcode = $request->postcode;
    $res = app(ProfileRepository::class)->deleteproviderpostcode($postcode);
    
    if($res){
        return response()->json(['success' => true], 200);
    }else{
        return response()->json(['error' => true], 201); 
    }
}



   // use CanUpload;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index(Request $request)
    {
        $postcodes = Postcode::query();
        
        if ($request->has('id')) {
            $postcodes = $postcodes->where('id', $request->get('id'));
        }
        
		if ($request->has('post_code')) {
			$postcodes = $postcodes->where('postcode', 'LIKE', '%'.$request->get('post_code').'%');
		}
		if ($request->has('subrub')) {
			$postcodes = $postcodes->where('subrub', 'LIKE', '%'.$request->get('subrub').'%');
		}
        $postcodes = $postcodes->paginate(20);
        return (new PostcodeCollection($postcodes));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    

    public function search_postcode(request $request){

    $postcode = Postcode::query();
      if ($request->has('postcode')) {

            $postcode = postcode::select('id as value',DB::raw("CONCAT(postcode,', ',suburb,', ',state) AS text"),'postcode')->where('hide', 0)
                ->where(function ($query) use ($request) {
                    $query
                        ->where('postcode', 'LIKE', '%'.$request->get('postcode').'%')
                        ->orwhere('suburb', 'LIKE', '%'.$request->get('postcode').'%');
                });
        }

        $postcode = $postcode->get();
        return response()->json(['success'=>true,'data'=>$postcode],200);

    }

    public function post(PostcodeRequest $request, Postcode $postcode)
    {
        $postcode = Postcode::firstOrNew(['id' => $request->get('id')]);
        $postcode->id = $request->get('id');
		$postcode->postcode_uuid = $request->get('postcode_uuid');
		$postcode->post_code = $request->get('post_code');
		$postcode->subrub = $request->get('subrub');

        $postcode->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }
	 
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $postcode = Postcode::find($request->get('id'));
        $postcode->delete();
        return response()->json(['no_content' => true], 200);
    }

    /**
     * Restore the specified resource to storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function restore(Request $request)
    // {
    //     $postcode = Postcode::withTrashed()->find($request->get('id'));
    //     $postcode->restore();
    //     return response()->json(['no_content' => true], 200);
    // }
}
