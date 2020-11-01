<?php

namespace App\Http\Controllers\Backend\API;

use App\Setting;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\SettingRequest;
use App\Http\Resources\SettingCollection;
use App\Http\Resources\Setting as SettingResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use Hash;
use DB;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $settings = Setting::query();
        
        if ($request->has('id')) {
			$settings = $settings->where('id', 'LIKE', '%'.$request->get('id').'%');
		}
		
		if ($request->has('type')) {
			$settings = $settings->where('type', 'LIKE', '%'.$request->get('type').'%');
		}
		if ($request->has('key')) {
			$settings = $settings->where('key', 'LIKE', '%'.$request->get('key').'%');
		}
		if ($request->has('value')) {
			$settings = $settings->where('value', 'LIKE', '%'.$request->get('value').'%');
		}
        $settings = $settings->paginate(20);
        return (new SettingCollection($settings));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(SettingRequest $request, Setting $setting)
    {
        $setting = Setting::firstOrNew(['id' => $request->get('id')]);
        $setting->id = $request->get('id');
		$setting->type = $request->get('type');
		$setting->key = $request->get('key');
		$setting->value = $request->get('value');

        $setting->save();

        $responseCode = $request->get('id') ? 200 : 201;
        return response()->json(['saved' => true], $responseCode);
    }

    // for get settings
    public function getsettings(Request $request)
    {
        $settings = Setting::query();
        
        $settings = $settings->paginate(20);
        return (new SettingCollection($settings));
    }

    // for add payment settings
    public function addpaymentsettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'strip_secret_key' => 'required',
            'strip_publishable_key' => 'required',
            'commission' => 'required|numeric'
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

        $type = "payment";

        $types = DB::table('settings')->select('*')->where('type', $type)->get();
        // print_r($types[0]);exit;
        $uuid = $types[0]->uuid;

        if($types[0]->type == $type){
            $setting = Setting::firstOrNew(['uuid' => $uuid]);
            $setting->type = "payment";
            $setting->key = "stripe";
            $strip_secret_key = $request->get('strip_secret_key');
            $strip_publishable_key = $request->get('strip_publishable_key');
            $commission = $request->get('commission');
            $result["strip_secret_key"] = $strip_secret_key;
            $result["strip_publishable_key"] = $strip_publishable_key;
            $result["commission"] = $commission;
            $setting->value = json_encode($result);

            $setting->save();

            $responseCode = $request->get('id') ? 200 : 201;
            return response()->json(['saved' => true], $responseCode);
        } else{
            $setting = Setting::firstOrNew(['id' => $request->get('id')]);
            $setting->type = "payment";
            $setting->key = "stripe";
            $strip_secret_key = $request->get('strip_secret_key');
            $strip_publishable_key = $request->get('strip_publishable_key');
            $commission = $request->get('commission');
            $result["strip_secret_key"] = $strip_secret_key;
            $result["strip_publishable_key"] = $strip_publishable_key;
            $result["commission"] = $commission;
            $setting->value = json_encode($result);

            $setting->save();

            $responseCode = $request->get('id') ? 200 : 201;
            return response()->json(['saved' => true], $responseCode);
        }
 
    }

    // for add sms settings
    public function addsmssettings(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'sms_provider' => 'required',
            'sms_api_key' => 'required',
            'sms_host_api_endpoint' => 'required'
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

        $type = "sms";

        $types = DB::table('settings')->select('*')->where('type', $type)->get();
        // print_r($types[0]);exit;
        $uuid = $types[0]->uuid;

        if($types[0]->type == $type){
            $setting = Setting::firstOrNew(['uuid' => $uuid]);
            $setting->type = "sms";
            $setting->key = "sms";
            $sms_provider = $request->get('sms_provider');
            $sms_api_key = $request->get('sms_api_key');
            $sms_host_api_endpoint = $request->get('sms_host_api_endpoint');
            $result["sms_provider"] = $sms_provider;
            $result["sms_api_key"] = $sms_api_key;
            $result["sms_host_api_endpoint"] = $sms_host_api_endpoint;
            $setting->value = json_encode($result);

            $setting->save();

            $responseCode = $request->get('id') ? 200 : 201;
            return response()->json(['saved' => true], $responseCode);
        } else{
            $setting = Setting::firstOrNew(['id' => $request->get('id')]);
            $setting->type = "sms";
            $setting->key = "sms";
            $sms_provider = $request->get('sms_provider');
            $sms_api_key = $request->get('sms_api_key');
            $sms_host_api_endpoint = $request->get('sms_host_api_endpoint');
            $result["sms_provider"] = $sms_provider;
            $result["sms_api_key"] = $sms_api_key;
            $result["sms_host_api_endpoint"] = $sms_host_api_endpoint;
            $setting->value = json_encode($result);

            $setting->save();

            $responseCode = $request->get('id') ? 200 : 201;
            return response()->json(['saved' => true], $responseCode);
        }
    }

    // for add email settings
    public function addemailsettings(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'smtp_host' => 'required',
            'smtp_port' => 'required|numeric',
            'smtp_email' => 'required',
            'smtp_password' => 'required'
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

        $type = "email";

        $types = DB::table('settings')->select('*')->where('type', $type)->get();
        // print_r($types[0]);exit;
        $uuid = $types[0]->uuid;

        if($types[0]->type == $type){
            $setting = Setting::firstOrNew(['uuid' => $uuid]);
            $setting->type = "email";
            $setting->key = "email";
            $smtp_host = $request->get('smtp_host');
            $smtp_port = $request->get('smtp_port');
            $smtp_email = $request->get('smtp_email');
            $smtp_password = $request->get('smtp_password');
            $result["smtp_host"] = $smtp_host;
            $result["smtp_port"] = $smtp_port;
            $result["smtp_email"] = $smtp_email;
            $result["smtp_password"] = $smtp_password;
            $setting->value = json_encode($result);

            $setting->save();

            $responseCode = $request->get('id') ? 200 : 201;
            return response()->json(['saved' => true], $responseCode);
        } else{
            $setting = Setting::firstOrNew(['id' => $request->get('id')]);
            $setting->type = "email";
            $setting->key = "email";
            $smtp_host = $request->get('smtp_host');
            $smtp_port = $request->get('smtp_port');
            $smtp_email = $request->get('smtp_email');
            $smtp_password = $request->get('smtp_password');
            $result["smtp_host"] = $smtp_host;
            $result["smtp_port"] = $smtp_port;
            $result["smtp_email"] = $smtp_email;
            $result["smtp_password"] = $smtp_password;
            $setting->value = json_encode($result);

            $setting->save();

            $responseCode = $request->get('id') ? 200 : 201;
            return response()->json(['saved' => true], $responseCode);
        }
    }

    // for add firebase settings
    public function addfirebasesettings(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'server_key' => 'required'
        ]);
        
        if($validator->fails()){
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 401);
        }

        $type = "firebase";

        $types = DB::table('settings')->select('*')->where('type', $type)->get();
        // print_r($types[0]);exit;
        $uuid = $types[0]->uuid;

        if($types[0]->type == $type){
            $setting = Setting::firstOrNew(['uuid' => $uuid]);
            $setting->type = "firebase";
            $setting->key = "firebase";
            $server_key = $request->get('server_key');
            $result["server_key"] = $server_key;
            $setting->value = json_encode($result);

            $setting->save();

            $responseCode = $request->get('id') ? 200 : 201;
            return response()->json(['saved' => true], $responseCode);
        } else{
            $setting = Setting::firstOrNew(['id' => $request->get('id')]);
            $setting->type = "firebase";
            $setting->key = "firebase";
            $server_key = $request->get('server_key');
            $result["server_key"] = $server_key;
            $setting->value = json_encode($result);

            $setting->save();

            $responseCode = $request->get('id') ? 200 : 201;
            return response()->json(['saved' => true], $responseCode);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $setting = Setting::find($request->get('id'));
        $setting->delete();
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
        $setting = Setting::withTrashed()->find($request->get('id'));
        $setting->restore();
        return response()->json(['no_content' => true], 200);
    }
}
