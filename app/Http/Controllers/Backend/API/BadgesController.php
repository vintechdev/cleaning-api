<?php
namespace App\Http\Controllers\Backend\API;

use App\Services\BadgesService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BadgesController extends Controller
{
    /**
     * @var BadgesService
     */
    private $badgesService;
    /**
     * BadgesController constructor.
     * @param BadgesService $badgesService
     */
    public function __construct(BadgesService $badgesService)
    {
        $this->badgesService = $badgesService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $badges =  $this->badgesService->getAll($request->all());
        return response()->json(['data', $badges], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request,$users_uuid)
    {
        $request->validate([
           'badge_label' => 'required|string|min:3',
           'badge_description' => 'required|string|min:3',
        ]);

        // TODO: Upload functionality after discussion
        $badge = $this->badgesService->create($request->all());

        return response()
            ->json(['saved' => true, 'success' => true, 'data', $badge],
                200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $badge = $this->badgesService->edit($id);
        return response()->json($badge->toArray(),200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'badge_label' => 'required|string|min:3',
            'badge_description' => 'required|string|min:3',
        ]);

        $badge =  $this->badgesService->update($id, $request->all());
        return response()->json(['success' => true, 'data' => $badge], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        try {
           $isDeleted = $this->badgesService->delete($id);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage(), 'success'=> false]);
        }

        if ($isDeleted) {
            return  response()->json(['message' => "Deleted successfully", 'success'=> true], 200);
        }

        return  response()->json(['message' => "Error while Deletion", 'success'=> false]);
    }
}
