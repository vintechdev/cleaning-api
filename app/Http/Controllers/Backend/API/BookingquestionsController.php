<?php

namespace App\Http\Controllers\Backend\API;

use App\Bookingquestion;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\BookingquestionRequest;
use App\Http\Resources\BookingquestionCollection;
use App\Http\Resources\Bookingquestion as BookingquestionResource;
use App\Http\Controllers\Controller;

class BookingquestionsController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_multipal_question(Request $request){

        $record = $request->question;
        if(! empty($record))
        {
               
            foreach($record as $key => $question)
            {    
            $bookingquestion = new Bookingquestion;
            $bookingquestion->booking_id = 1;
            $bookingquestion->service_question_id = $question['service_question_id'];
            $bookingquestion->answer = $question['answer'];
            $bookingquestion->save();           
            }

            return response()->json(['saved' => true], 201);

        }
        else{
            return response()->json(['saved' => false], 404);
        }
    }
    public function index(Request $request)
    {
        $bookingquestions = Bookingquestion::query();
        
		if ($request->has('booking_id')) {
			$bookingquestions = $bookingquestions->where('booking_id', 'LIKE', '%'.$request->get('booking_id').'%');
		}
		if ($request->has('service_question_id')) {
			$bookingquestions = $bookingquestions->where('service_question_id', 'LIKE', '%'.$request->get('service_question_id').'%');
		}
		if ($request->has('answer')) {
			$bookingquestions = $bookingquestions->where('answer', 'LIKE', '%'.$request->get('answer').'%');
		}
        $bookingquestions = $bookingquestions->paginate(20);
        return (new BookingquestionCollection($bookingquestions));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(BookingquestionRequest $request, Bookingquestion $bookingquestion)
    {
        $bookingquestion = Bookingquestion::firstOrNew(['id' => $request->get('id')]);
        $bookingquestion->id = $request->get('id');
		$bookingquestion->booking_question_uuid = $request->get('booking_question_uuid');
		$bookingquestion->booking_id = $request->get('booking_id');
		$bookingquestion->service_question_id = $request->get('service_question_id');
		$bookingquestion->answer = $request->get('answer');

        $bookingquestion->save();

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
        $bookingquestion = Bookingquestion::find($request->get('id'));
        $bookingquestion->delete();
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
        $bookingquestion = Bookingquestion::withTrashed()->find($request->get('id'));
        $bookingquestion->restore();
        return response()->json(['no_content' => true], 200);
    }
}
