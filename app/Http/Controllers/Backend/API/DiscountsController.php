<?php
namespace App\Http\Controllers\Backend\API;

use App\Services\DiscountManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DiscountsController extends Controller
{
    /**
     * @var DiscountManager
     */
    private $discountManager;
    /**
     * BadgesController constructor.
     * @param DiscountManager $discountManager
     */
    public function __construct(DiscountManager $discountManager)
    {
        $this->discountManager = $discountManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $discountList =  $this->discountManager->getAll($request->all());
        return response()->json(['data' => $discountList], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $discount = $this->discountManager->create($request->all());
        } catch (\InvalidArgumentException $exception) {
            return response()->json($exception->getMessage(), 422);
        } catch (\Exception $exception) {
            logger()->error($exception->getMessage());
            return response()->json('Something went wrong. Please contact administrator.', 500);
        }

        return response()
            ->json(['saved' => true, 'success' => true, 'data' => $discount],
                201);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $discount = $this->discountManager->getDiscountDetailsById($id);
        return response()->json($discount->toArray(),200);
    }

    public function update(Request $request, $id)
    {
        try {
            $discount =  $this->discountManager->update($id, $request->all());
        } catch (\InvalidArgumentException $exception) {
            return response()->json($exception->getMessage(), 422);
        } catch (\Exception $exception) {
            logger()->error($exception->getMessage());
            return response()->json('Something went wrong. Please contact administrator.', 500);
        }


        return response()->json(['success' => true, 'data' => $discount], 201);
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
           $isDeleted = $this->discountManager->delete($id);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage(), 'success'=> false]);
        }

        if ($isDeleted) {
            return  response()->json(['message' => "Deleted successfully", 'success'=> true], 200);
        }

        return  response()->json(['message' => "Error while Deletion", 'success'=> false]);
    }

    /**
     * @return array
     */
    public function getDiscountTypes(): array
    {
        return $this->discountManager->getDiscountTypes();
    }
}
