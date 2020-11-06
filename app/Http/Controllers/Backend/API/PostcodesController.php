<?php

namespace App\Http\Controllers\Backend\API;

use App\Postcode;
use Illuminate\Http\Request;
//use Shahnewaz\Redprint\Traits\CanUpload;
use App\Http\Requests\Backend\PostcodeRequest;
use App\Http\Resources\PostcodeCollection;
use App\Http\Resources\Postcode as PostcodeResource;
use App\Http\Controllers\Controller;

class PostcodesController extends Controller
{
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
            $postcodes = $postcodes->where('id', 'LIKE', '%'.$request->get('id'));
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
    

    public function search_provider_postcode(request $request){

    $postcode = Postcode::query();
      if ($request->has('postcode')) {

            $postcode = postcode::select('postcode','suburb','state')->where('postcode', 'LIKE', '%'.$request->get('postcode').'%')->orwhere('suburb', 'LIKE', '%'.$request->get('postcode').'%');
        }

        $postcode = $postcode->paginate(10);
        return (new ServicecategoryCollection($postcode));

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
