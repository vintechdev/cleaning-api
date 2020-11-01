<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Backend\API')->prefix('v1/public')->group(function () {

	 Route::get('plans', 'PlansController@get_all_plan')->name('api.plan.get_all_plan');
	 
	 Route::get('providersort', 'ServicecategoryController@provider_sort');

     Route::get('providerfilter', 'ServicecategoryController@provider_filter');

     Route::get('providersearch', 'ServicecategoryController@provider_search');

     Route::get('servicecategories', 'ServicecategoryController@get_all_servicecategory')->name('api.servicecategory.get_all_servicecategory');
     Route::get('postcodesearch', 'ServicecategoryController@postcode_search'); 

     Route::get('get_default_service', 'ServiceController@get_default_service'); 
 
 });

// Route::middleware(['role:public'])namespace('Backend\API')->prefix('v1/public')->group(function () {
    
//     Route::get('plans', 'PlansController@get_all_plan')->name('api.plan.get_all_plan');
    
//  });


	