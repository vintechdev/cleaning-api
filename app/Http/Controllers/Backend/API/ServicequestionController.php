<?php

namespace App\Http\Controllers\Backend\API;

use App\Servicequestion;
use App\Service;
use DB;
use App\Http\Resources\ServiceCollection;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\ServicequestionRequest;
use App\Http\Resources\ServicequestionCollection;
use App\Http\Resources\Servicequestion as ServicequestionResource;
use App\Http\Controllers\Controller;

class ServicequestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $servicequestions = Servicequestion::query();
    
        if ($request->has('id')) {
			$servicequestions = $servicequestions->where('id', 'LIKE', '%'.$request->get('id').'%');
		}
		if ($request->has('uuid')) {
			$servicequestions = $servicequestions->where('uuid', 'LIKE', '%'.$request->get('uuid').'%');
		}
		if ($request->has('service_id')) {
			$servicequestions = $servicequestions->where('service_id', 'LIKE', '%'.$request->get('service_id').'%');
		}
		if ($request->has('question_type')) {
			$servicequestions = $servicequestions->where('question_type', 'LIKE', '%'.$request->get('question_type').'%');
		}
		if ($request->has('question_values')) {
			$servicequestions = $servicequestions->where('question_values', 'LIKE', '%'.$request->get('question_values').'%');
		}
		if ($request->has('title')) {
			$servicequestions = $servicequestions->where('title', 'LIKE', '%'.$request->get('title').'%');
		}
		if ($request->has('question')) {
			$servicequestions = $servicequestions->where('question', 'LIKE', '%'.$request->get('question').'%');
		}
		if ($request->has('description')) {
			$servicequestions = $servicequestions->where('description', 'LIKE', '%'.$request->get('description').'%');
		}
        $servicequestions = $servicequestions->paginate(20);
        return (new ServicequestionCollection($servicequestions));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(ServicequestionRequest $request, Servicequestion $servicequestion)
    {
        $servicequestion = Servicequestion::firstOrNew(['id' => $request->get('id')]);
        $servicequestion->id = $request->get('id');
		$servicequestion->service_question_uuid = $request->get('service_question_uuid');
		$servicequestion->service_id = $request->get('service_id');
		$servicequestion->question_type = $request->get('question_type');
		$servicequestion->question_values = $request->get('question_values');
		$servicequestion->title = $request->get('title');
		$servicequestion->question = $request->get('question');
		$servicequestion->description = $request->get('description');

        $servicequestion->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }

    // for get service questions by service uuid
    public function getservicequestions(Request $request, $uuid)
    {
        // $user = Auth::user();
        // $user_id = $user->id;
        
        // $Service = Service::query();
        // $Service = $Service->where('uuid', 'LIKE', '%'.$uuid);
        // $Service = $Service->paginate(20);
        // $Service = new ServiceCollection($Service);
        $Service = DB::table('services')->where('uuid', $uuid )->orwhere('name', $uuid)->get();
        $service_id = $Service[0]->id;
        
        // $Servicequestion = Servicequestion::query();
        
		if ($uuid) {
            // $Servicequestion = $Servicequestion->where('service_id', 'LIKE', '%'.$service_id);
            $Servicequestion = DB::table('service_questions')->where('service_id', $service_id)->get();
		}
        // print_r($Servicequestion);exit;
		
        // $Servicequestion = $Servicequestion->paginate(20);
        // $Servicequestion = new ServicequestionCollection($Servicequestion);
        // return (new ServicequestionCollection($Servicequestion));
        return $Servicequestion;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $servicequestion = Servicequestion::find($request->get('id'));
        $servicequestion->delete();
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
        $servicequestion = Servicequestion::withTrashed()->find($request->get('id'));
        $servicequestion->restore();
        return response()->json(['no_content' => true], 200);
    }
}
