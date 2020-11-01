<?php

namespace App\Http\Controllers\Backend\API;

use App\Providermetadatum;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\ProvidermetadatumRequest;
use App\Http\Resources\ProvidermetadatumCollection;
use App\Http\Resources\Providermetadatum as ProvidermetadatumResource;
use App\Http\Controllers\Controller;
use Auth;
use Hash;
class ProvidermetadataController extends Controller
{
    //use CanUpload;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
   public function get_all_providermetadata(Request $request)
    {
        $providermetadata = Providermetadatum::query();
        $providermetadata = $providermetadata->paginate(20);
        return (new ProvidermetadatumCollection($providermetadata));
    }
    public function get(Request $request,$providermetadata_uuid)
    {

//$user=auth('api')->user();
//print_r($user);

        $userid=auth('api')->user()->id;

         $providermetadata = Providermetadatum::query();

        if ($providermetadata_uuid) {
            $providermetadata = $providermetadata->where('id',$providermetadata_uuid)->orWhere('uuid',$providermetadata_uuid);
        }
        
       if ($request->has('provider_user_id')) {
            $providermetadata = $providermetadata->where('provider_user_id', 'LIKE', '%'.$request->get('provider_user_id').'%');
        }
        if ($request->has('skills')) {
            $providermetadata = $providermetadata->where('skills', 'LIKE', '%'.$request->get('skills').'%');
        }
        if ($request->has('is_agency')) {
            $providermetadata = $providermetadata->where('is_agency', 'LIKE', '%'.$request->get('is_agency').'%');
        }
       
        if ($request->has('bank_account_name')) {
            $providermetadata = $providermetadata->where('bank_account_name', 'LIKE', '%'.$request->get('bank_account_name').'%');
        }
        if ($request->has('bank_bsb')) {
            $providermetadata = $providermetadata->where('bank_bsb', 'LIKE', '%'.$request->get('bank_bsb').'%');
        }
        if ($request->has('stripe_connected_account_id')) {
            $providermetadata = $providermetadata->where('stripe_connected_account_id', 'LIKE', '%'.$request->get('stripe_connected_account_id').'%');
        }
        if ($request->has('service_fee_percentage')) {
            $providermetadata = $providermetadata->where('service_fee_percentage', 'LIKE', '%'.$request->get('service_fee_percentage').'%');
        }
       
        $providermetadata = $providermetadata->paginate(20);
        return (new ProvidermetadatumCollection($providermetadata));
    }

     
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     
    public function add_bankdata(ProvidermetadatumRequest $request)
    {
       $providermetadatum = Providermetadatum::firstOrNew(['id' => $request->get('id')]);
          $providermetadatum->id = $request->get('id');
        $providermetadatum->uuid = $request->get('uuid');
        $providermetadatum->provider_user_id = $request->get('provider_user_id');
        $providermetadatum->is_agency = $request->get('is_agency');
        $providermetadatum->bank_account_name = $request->get('bank_account_name');
        $providermetadatum->bank_bsb = $request->get('bank_bsb');
        $providermetadatum->bank_account_number = $request->get('bank_account_number');
       

        $providermetadatum->save();

        

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }
     public function edit_bankdata(ProvidermetadatumRequest $request,$providermetadatum_uuid)
    {
       
        $providermetadatum = Providermetadatum::firstOrNew(['uuid' => $providermetadatum_uuid]);
        $providermetadatum->provider_user_id = $request->get('provider_user_id');
        $providermetadatum->is_agency = $request->get('is_agency');
        $providermetadatum->bank_account_name = $request->get('bank_account_name');
        $providermetadatum->bank_bsb = $request->get('bank_bsb');
        $providermetadatum->bank_account_number = $request->get('bank_account_number');
        $providermetadatum->service_fee_percentage = $request->get('service_fee_percentage');

        $providermetadatum->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => $providermetadatum], $responseCode);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function delete_providermetadata(Request $request,$providermetadatum_uuid)
    {

        $providermetadatum = Providermetadatum::where('uuid',$providermetadatum_uuid)->orWhere('id',$providermetadatum_uuid)->first();
        if ($providermetadatum != null) {
            $providermetadatum->delete();
            return response()->json(['no_content' => $providermetadatum], 200);
        }
        else{
            return response()->json(['no_content' => 'Wrong ID!!']);
        }

    }
    
}
