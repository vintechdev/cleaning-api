<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// changes for bhargav
// if (isset($_SERVER['HTTP_ORIGIN'])) {
//     header("Access-Control-Allow-Origin: *");
//     header('Access-Control-Allow-Credentials: false');
//     header('Access-Control-Max-Age: 86400');    // cache for 1 day
// }

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Redprint Auth Route
// You can implement your own Auth endpoint and method
// Route::post(
//     'permissible/auth/token',
//     '\Shahnewaz\Permissible\Http\Controllers\API\AuthController@postAuthenticate'
// )->name('permissible.auth.token');

// Route::post('/register', '\Shahnewaz\Permissible\Http\Controllers\API\AuthController@register')->name('register');

// API Routes
// Access them like: /api/v1/route
// Route::middleware(['jwt.auth', 'role:admin'])->namespace('Backend\API')->prefix('v1/backend')->group(function () {
// Route::middleware(['jwt.auth'])->namespace('Backend\API')->prefix('v1/backend')->group(function () {

/*Run route without token*/
// Route::namespace('Backend\API')->prefix('v1/backend')->group(function () {

// Route::get('catagoriessearch', 'ServicecategoriesController@catagories_search')->middleware('auth_client');

//Route::get('servicecategories', 'ServicecategoriesController@index')->name('api.servicecategory.index');

// Route::get('plans', 'PlansController@get_all_plan')->name('api.plan.index');
// });

Route::namespace('Backend\API')->prefix('v1/public')->group(function () {
    Route::get('postcodes', 'PostcodesController@index')->name('api.postcode.index');
  
    Route::get('getallservicecategories', 'ServicecategoryController@GetAllCategories')->name('getallservicecategories');
	
	Route::get('plans', 'PlansController@get_all_plan')->name('api.plan.get_all_plan');
	
	

});

Route::namespace('Backend\API')->prefix('v1/customer')->group(function(){
    Route::get('getallprovider', 'CustomerusersController@getallprovider')->name('api.Customeruser.getallprovider');//->middleware(['scope:customer']);
	
    Route::get('getallservices', 'ServiceController@index')->name('api.Service.index');//->middleware(['scope:customer']);
    Route::get('getaddress', 'UseraddressController@getaddress')->name('api.Useraddress.getaddress');
	 
	  Route::get('getservicequestions/{uuid}', 'ServicequestionController@getservicequestions')->name('api.Servicequestion.getservicequestions');
    Route::get('geserviceprice', 'ServiceController@geserviceprice')->name('geserviceprice');
});




// for passport
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
});
// Route::post('/oauth/token', 'AuthController@login');

// for email verify
Route::get('email/verify/{id}', 'VerificationApiController@verify')->name('verificationapi.verify');
Route::get('email/resend', 'VerificationApiController@resend')->name('verificationapi.resend');

Route::middleware(['auth:api', 'role:admin'])->namespace('Backend\API')->prefix('v1/admin')->group(function () {
   // Admin Route
   Route::post('addpaymentsettings', 'SettingController@addpaymentsettings')->name('api.Setting.addpaymentsettings')->middleware(['scope:admin']);
    Route::post('addsmssettings', 'SettingController@addsmssettings')->name('api.Setting.addsmssettings')->middleware(['scope:admin']);
    Route::post('addemailsettings', 'SettingController@addemailsettings')->name('api.Setting.addemailsettings')->middleware(['scope:admin']);
    Route::post('addfirebasesettings', 'SettingController@addfirebasesettings')->name('api.Setting.addfirebasesettings')->middleware(['scope:admin']);
    Route::get('getsettings', 'SettingController@getsettings')->name('api.Setting.getsettings')->middleware(['scope:admin']);

});

Route::middleware(['auth:api', 'role:customer'])->namespace('Backend\API')->prefix('v1/customer')->group(function () {
    // Customeruser Route

    //rakesh api

    Route::get('alternate_date/{booking_uuid}', 'BookingController@get_alternate_date')->name('customer_get_alternate_date')->middleware(['scope:customer']);
    Route::post('add_alternate_date', 'BookingController@add_alternate_date')->name('add_alternate_date')->middleware(['scope:customer']);
    Route::delete('delete_alternate_date/{uuid}', 'BookingController@delete_alternate_date')->name('delete_alternate_date')->middleware(['scope:customer']);
    Route::patch('edit_alternate_date/{uuid}', 'BookingController@edit_alternate_date')->name('edit_alternate_date')->middleware(['scope:customer']);

    Route::get('promocode_discount', 'BookingController@promocode_discount')->name('promocode_discount')->middleware(['scope:customer']);
    Route::post('add_booking', 'BookingController@add_booking')->name('add_booking')->middleware(['scope:customer']);
    Route::post('add_multipal_question', 'BookingquestionsController@add_multipal_question')->name('add_multipal_question')->middleware(['scope:customer']);
    Route::post('add_multipal_service', 'BookingserviceController@add_multipal_service')->name('add_multipal_service')->middleware(['scope:customer']);
    Route::get('getcleanardata/{uuid}', 'UserreviewController@getcleanardata')->name('api.Userreview.getcleanardata')->middleware(['scope:customer']);


    Route::get('getallusers', 'CustomerusersController@index')->name('api.Customeruser.index')->middleware(['scope:customer']);
    Route::get('profile_view', 'CustomerusersController@profile_view')->name('api.Customeruser.profile_view')->middleware(['scope:customer']);
    Route::patch('change_password', 'CustomerusersController@change_password')->name('api.Customeruser.change_password')->middleware(['scope:customer']);
    Route::patch('profile_update', 'CustomerusersController@profile_update')->name('api.Customeruser.profile_update')->middleware(['scope:customer']);



    Route::patch('card_update', 'CustomermetadataController@card_update')->name('api.Customermetadata.card_update')->middleware(['scope:customer']);

     Route::patch('card_dataadd', 'CustomermetadataController@card_dataadd')->name('api.Customermetadata.card_dataadd')->middleware(['scope:customer']);

    Route::patch('card_updatedata', 'CustomermetadataController@index')->name('api.Customermetadata.index')->middleware(['scope:customer']);

    //Route::get('getallservices', 'ServiceController@index')->name('api.Service.index')->middleware(['scope:customer']);

    Route::post('addservices/{uuid}', 'ServiceController@addservices')->name('api.Service.addservices')->middleware(['scope:customer']);

  //  Route::get('getservicequestions/{uuid}', 'ServicequestionController@getservicequestions')->name('api.Servicequestion.getservicequestions')->middleware(['scope:customer']);

    Route::patch('editservices/{uuid}', 'ServiceController@editservices')->name('api.Service.editservices')->middleware(['scope:customer']);

    Route::post('addaddress', 'UseraddressController@addaddress')->name('api.Useraddress.addaddress')->middleware(['scope:customer']);

    

    Route::patch('editaddress/{uuid}', 'UseraddressController@editaddress')->name('api.Useraddress.editaddress')->middleware(['scope:customer']);

    Route::get('getdashboard', 'AnnoucementController@getdashboard')->name('api.Annoucement.getdashboard')->middleware(['scope:customer']);

    Route::get('getnotifications', 'NotificationController@getnotifications')->name('api.Notification.getnotifications')->middleware(['scope:customer']);

    Route::patch('editnotifications/{uuid}', 'NotificationController@editnotifications')->name('api.Notification.editnotifications')->middleware(['scope:customer']);
    Route::get('getallbookingdetails', 'BookingController@getallbookingdetails')->name('api.Booking.getallbookingdetails')->middleware(['scope:customer']);

    Route::get('getpendingbookingdetails', 'BookingController@getpendingbookingdetails')->name('api.Booking.getpendingbookingdetails')->middleware(['scope:customer']);
    Route::get('getpastbookingdetails', 'BookingController@getpastbookingdetails')->name('api.Booking.getpastbookingdetails')->middleware(['scope:customer']);

    Route::get('getfuturebookingdetails', 'BookingController@getfuturebookingdetails')->name('api.Booking.getfuturebookingdetails')->middleware(['scope:customer']);

    Route::get('getpastbookingdetails', 'BookingController@getpastbookingdetails')->name('api.Booking.getpastbookingdetails')->middleware(['scope:customer']);
    Route::patch('cancelbooking/{uuid}', 'BookingController@cancelbooking')->name('api.Booking.cancelbooking')->middleware(['scope:customer']);
      Route::post('cancelbooking/{uuid}', 'BookingController@cancelbooking')->name('api.Booking.cancelbooking')->middleware(['scope:customer']);
    Route::get('getpaymenthistory', 'PaymentController@getpaymenthistory')->name('api.Payment.getpaymenthistory')->middleware(['scope:customer']);

    Route::get('getpaymentsettings', 'CustomermetadataController@getpaymentsettings')->name('api.Customermetadata.getpaymentsettings')->middleware(['scope:customer']);

    Route::get('getuserreviewdata/{uuid}', 'UserreviewController@getuserreviewdata')->name('api.Userreview.getuserreviewdata')->middleware(['scope:customer']);

    Route::post('addproviderreview/{uuid}', 'UserreviewController@addproviderreview')->name('api.Userreview.addproviderreview')->middleware(['scope:customer']);

    Route::get('getratingreview', 'UserreviewController@getratingreview')->name('api.Userreview.getratingreview')->middleware(['scope:customer']);

    Route::get('getcancelbookingdata/{uuid}', 'UserreviewController@getcancelbookingdata')->name('api.Userreview.getcancelbookingdata')->middleware(['scope:customer']);

    Route::get('getapprovedbookingdetails', 'BookingController@getapprovedbookingdetails')->name('api.Booking.getapprovedbookingdetails')->middleware(['scope:customer']);

    Route::get('getcancelledbookingdetails', 'BookingController@getcancelledbookingdetails')->name('api.Booking.getcancelledbookingdetails')->middleware(['scope:customer']);

    Route::get('getrejectedbookingdetails', 'BookingController@getrejectedbookingdetails')->name('api.Booking.getrejectedbookingdetails')->middleware(['scope:customer']);

    Route::get('getalterbookdata/{uuid}', 'BookingController@getalterbookdata')->name('api.Booking.getalterbookdata')->middleware(['scope:customer']);
	
   // Route::get('getallprovider', 'CustomerusersController@getallprovider')->name('api.Customeruser.getallprovider')->middleware(['scope:customer']);
	
    Route::patch('customer_edit_booking/{uuid}', 'BookingController@customer_edit_booking')->name('api.Booking.customer_edit_booking')->middleware(['scope:customer']);
    Route::post('customer_add_agency', 'BookingController@customer_add_agency')->name('customer_add_agency')->middleware(['scope:customer']);
    Route::patch('change_announcement_status/{uuid}', 'AnnoucementController@change_announcement_status')->name('api.AnnoucementController.change_announcement_status')->middleware(['scope:customer']);
    Route::get('getallbookingstatus', 'BookingstatusController@getallbookingstatus')->name('api.Bookingstatus.getallbookingstatus')->middleware(['scope:customer']);
    Route::get('getappointment/{uuid}', 'BookingController@getappointment')->name('api.Booking.getappointment')->middleware(['scope:customer']);
    Route::patch('change_booking_status/{uuid}', 'BookingController@change_booking_status')->name('api.Booking.change_booking_status')->middleware(['scope:customer']);
  });

 Route::middleware(['auth:api', 'role:provider'])->namespace('Backend\API')->prefix('v1/provider')->group(function () {
    // provider Route

    // yash api
    Route::patch('provider_accept_booking/{uuid}', 'BookingrequestprovidersController@provider_accept')->name('api.Bookingrequestproviders.edit')->middleware(['scope:provider']);
    Route::patch('provider_reject_booking/{uuid}', 'BookingrequestprovidersController@provider_reject')->name('api.Bookingrequestproviders.edit')->middleware(['scope:provider']);
    Route::get('getappointment/{uuid}', 'BookingController@provider_getappointment')->name('api.Booking.provider_getappointment')->middleware(['scope:provider']);
    Route::patch('change_booking_status/{uuid}', 'BookingController@change_booking_status')->name('api.Booking.change_booking_status')->middleware(['scope:provider']);

    Route::get('getallusers', 'CustomerusersController@index')->name('api.Customeruser.index')->middleware(['scope:provider']);
     //rakesh api

    Route::get('alternate_date/{booking_uuid}', 'BookingController@get_alternate_date')->name('provider_get_alternate_date')->middleware(['scope:provider']);
    Route::patch('select_alternate_date/{uuid}', 'BookingController@provider_select_alternate_date')->name('provider_select_alternate_date')->middleware(['scope:provider']);
    Route::patch('cancelbooking/{uuid}', 'BookingController@provider_cancelbooking')->name('api.Booking.cancelbooking')->middleware(['scope:provider']);
    Route::get('promocode_discount', 'BookingController@promocode_discount')->name('promocode_discount')->middleware(['scope:provider']);
    Route::post('add_booking', 'BookingController@add_booking')->name('add_booking')->middleware(['scope:provider']);
     Route::post('add_multipal_question', 'BookingquestionsController@add_multipal_question')->name('add_multipal_question')->middleware(['scope:provider']);
     Route::post('add_multipal_service', 'BookingserviceController@add_multipal_service')->name('add_multipal_service')->middleware(['scope:provider']);
      Route::get('getcleanardata/{uuid}', 'UserreviewController@getcleanardata')->name('api.Userreview.getcleanardata')->middleware(['scope:provider']);


     // provider Route vijay
    Route::get('providermetadata', 'ProvidermetadataController@get_all_providermetadata')->name('api.providermetadatum.get_all_providermetadata')->middleware(['scope:provider']);

    Route::get('providermetadata/{providermetadata_uuid}', 'ProvidermetadataController@get')->name('api.providermetadatum.get')->middleware(['scope:provider']);

    Route::post('providerbankdata', 'ProvidermetadataController@add_bankdata')->name('api.providermetadatum.save')->middleware(['scope:provider']);

    Route::patch('providermetadata/{providermetadata_uuid}', 'ProvidermetadataController@edit_bankdata')->name('api.providermetadata.edit')->middleware(['scope:provider']);

     Route::delete('providermetadata/{providermetadata_uuid}', 'ProvidermetadataController@delete_providermetadata')->name('api.providermetadata.delete')->middleware(['scope:provider']);

     Route::post('addaddress', 'UseraddressController@addaddress')->name('api.Useraddress.addaddress')->middleware(['scope:provider']);

     Route::get('getaddress', 'UseraddressController@getaddress')->name('api.Useraddress.getaddress')->middleware(['scope:provider']);

     Route::patch('editaddress/{uuid}', 'UseraddressController@editaddress')->name('api.Useraddress.editaddress')->middleware(['scope:provider']);

     Route::patch('change_password', 'CustomerusersController@change_password')->name('api.Customeruser.change_password')->middleware(['scope:customer']);

     Route::patch('profile_update', 'CustomerusersController@profile_update')->name('api.Customeruser.profile_update')->middleware(['scope:provider']);


     Route::post('addworking_hours', 'Working_hoursController@addworking_hours')->name('api.Working_hours.addworking_hours')->middleware(['scope:provider']);

     Route::get('getworking_hours', 'Working_hoursController@getworking_hours')->name('api.Working_hours.getworking_hours')->middleware(['scope:provider']);

     Route::patch('editworking_hours/{uuid}', 'Working_hoursController@editworking_hours')->name('api.Working_hours.editworking_hours')->middleware(['scope:provider']);


     Route::post('addskills', 'SkillsController@addskills')->name('api.Skills.addskills')->middleware(['scope:provider']);

     Route::get('getskills', 'SkillsController@getskills')->name('api.Skills.getskills')->middleware(['scope:provider']);

     Route::patch('editskills/{uuid}', 'SkillsController@editskills')->name('api.Skills.editskills')->middleware(['scope:provider']);

      Route::delete('deleteskills/{uuid}', 'SkillsController@deleteskills')->name('api.skills.deleteskills')->middleware(['scope:provider']);

      Route::post('addportfolios', 'ProviderportfoliosController@addportfolios')->name('api.Providerportfolios.addportfolios')->middleware(['scope:provider']);

     Route::get('getportfolios', 'ProviderportfoliosController@getportfolios')->name('api.Providerportfolios.getportfolios')->middleware(['scope:provider']);



      Route::delete('deleteportfolios/{uuid}', 'ProviderportfoliosController@deleteportfolios')->name('api.Providerportfolios.deleteportfolios')->middleware(['scope:provider']);


      Route::post('addservices', 'ServiceController@addservices')->name('api.Service.addservices')->middleware(['scope:provider']);
      Route::post('saveservices', 'ServiceController@saveservices')->name('api.Service.saveservices')->middleware(['scope:provider']);

      Route::get('getallservices', 'ServiceController@index')->name('api.Service.index')->middleware(['scope:provider']);


     Route::post('addservicesarea', 'ServiceareaController@addservicesarea')->name('api.Servicearea.addservicesarea')->middleware(['scope:provider']);

      Route::get('getallservicesarea', 'ServiceareaController@index')->name('api.Servicearea.index')->middleware(['scope:provider']);

      Route::delete('deleteservicearea/{uuid}', 'ServiceareaController@deleteservicearea')->name('api.Servicearea.deleteservicearea')->middleware(['scope:provider']);



     Route::get('getallbookingdetails', 'BookingController@getallbookingdetails')->name('api.Booking.getallbookingdetails')->middleware(['scope:provider']);
    Route::get('getpendingbookingdetails', 'BookingController@getpendingbookingdetails')->name('api.Booking.getpendingbookingdetails')->middleware(['scope:provider']);
    Route::get('getpastbookingdetails', 'BookingController@getpastbookingdetails')->name('api.Booking.getpastbookingdetails')->middleware(['scope:provider']);
    Route::get('getfuturebookingdetails', 'BookingController@getfuturebookingdetails')->name('api.Booking.getfuturebookingdetails')->middleware(['scope:provider']);
    Route::post('getallcalendardetails', 'BookingController@getallcalendardetails')->name('api.Booking.getallcalendardetails')->middleware(['scope:provider']);
    Route::get('getpaymenthistory', 'PaymentController@getpaymenthistory')->name('api.Payment.getpaymenthistory')->middleware(['scope:provider']);
     Route::get('getdashboard', 'AnnoucementController@getdashboard')->name('api.Annoucement.getdashboard')->middleware(['scope:provider']);

     Route::get('getuserreviewdata/{uuid}', 'UserreviewController@getuserreviewdata')->name('api.Userreview.getuserreviewdata')->middleware(['scope:provider']);
     Route::post('addproviderreview/{uuid}', 'UserreviewController@addproviderreview')->name('api.Userreview.addproviderreview')->middleware(['scope:provider']);
     Route::get('getratingreview', 'UserreviewController@getratingreview')->name('api.Userreview.getratingreview')->middleware(['scope:provider']);





       // provider Route vijay



 });

 
 Route::middleware(['auth:api', 'role:public'])->namespace('Backend\API')->prefix('v1/public')->group(function () {
    // Customeruser Route
    Route::get('getallusers', 'CustomerusersController@index')->name('api.Customeruser.index')->middleware(['scope:public']);

 });

Route::middleware(['auth:api'])->namespace('Backend\API')->prefix('v1/backend')->group(function () {
    //ROUTES

    // Endusermetadatum Route
    Route::get('endusermetadata', 'EndusermetadataController@index')->name('api.endusermetadatum.index');
    Route::get('/endusermetadata/{endusermetadatum}', 'EndusermetadataController@form')->name('api.endusermetadatum.form');
    Route::post('/endusermetadata/save', 'EndusermetadataController@post')->name('api.endusermetadatum.save');
    Route::post('/endusermetadata/{endusermetadatum}/delete', 'EndusermetadataController@delete')->name('api.endusermetadatum.delete');
    Route::post('/endusermetadata/{endusermetadatum}/restore', 'EndusermetadataController@restore')->name('api.endusermetadatum.restore');
    Route::post('/endusermetadata/{endusermetadatum}/force-delete', 'EndusermetadataController@forceDelete')->name('api.endusermetadatum.force-delete');


    // OnceBookingAlternateDate Route
    Route::get('onceBookingAlternateDates', 'OnceBookingAlternateDatesController@index')->name('api.onceBookingAlternateDate.index');
    Route::get('/onceBookingAlternateDates/{onceBookingAlternateDate}', 'OnceBookingAlternateDatesController@form')->name('api.onceBookingAlternateDate.form');
    Route::post('/onceBookingAlternateDates/save', 'OnceBookingAlternateDatesController@post')->name('api.onceBookingAlternateDate.save');
    Route::post('/onceBookingAlternateDates/{onceBookingAlternateDate}/delete', 'OnceBookingAlternateDatesController@delete')->name('api.onceBookingAlternateDate.delete');
    Route::post('/onceBookingAlternateDates/{onceBookingAlternateDate}/restore', 'OnceBookingAlternateDatesController@restore')->name('api.onceBookingAlternateDate.restore');
    Route::post('/onceBookingAlternateDates/{onceBookingAlternateDate}/force-delete', 'OnceBookingAlternateDatesController@forceDelete')->name('api.onceBookingAlternateDate.force-delete');


    // Paymentactivitylog Route
    Route::get('paymentactivitylogs', 'PaymentactivitylogsController@index')->name('api.paymentactivitylog.index');
    Route::get('/paymentactivitylogs/{paymentactivitylog}', 'PaymentactivitylogsController@form')->name('api.paymentactivitylog.form');
    Route::post('/paymentactivitylogs/save', 'PaymentactivitylogsController@post')->name('api.paymentactivitylog.save');
    Route::post('/paymentactivitylogs/{paymentactivitylog}/delete', 'PaymentactivitylogsController@delete')->name('api.paymentactivitylog.delete');
    Route::post('/paymentactivitylogs/{paymentactivitylog}/restore', 'PaymentactivitylogsController@restore')->name('api.paymentactivitylog.restore');
    Route::post('/paymentactivitylogs/{paymentactivitylog}/force-delete', 'PaymentactivitylogsController@forceDelete')->name('api.paymentactivitylog.force-delete');


    // PaymentAcivity Route
    Route::get('paymentAcivities', 'PaymentAcivitiesController@index')->name('api.paymentAcivity.index');
    Route::get('/paymentAcivities/{paymentAcivity}', 'PaymentAcivitiesController@form')->name('api.paymentAcivity.form');
    Route::post('/paymentAcivities/save', 'PaymentAcivitiesController@post')->name('api.paymentAcivity.save');
    Route::post('/paymentAcivities/{paymentAcivity}/delete', 'PaymentAcivitiesController@delete')->name('api.paymentAcivity.delete');
    Route::post('/paymentAcivities/{paymentAcivity}/restore', 'PaymentAcivitiesController@restore')->name('api.paymentAcivity.restore');
    Route::post('/paymentAcivities/{paymentAcivity}/force-delete', 'PaymentAcivitiesController@forceDelete')->name('api.paymentAcivity.force-delete');


    // Payment Route
    Route::get('payments', 'PaymentsController@index')->name('api.payment.index');
    Route::get('/payments/{payment}', 'PaymentsController@form')->name('api.payment.form');
    Route::post('/payments/save', 'PaymentsController@post')->name('api.payment.save');
    Route::post('/payments/{payment}/delete', 'PaymentsController@delete')->name('api.payment.delete');
    Route::post('/payments/{payment}/restore', 'PaymentsController@restore')->name('api.payment.restore');
    Route::post('/payments/{payment}/force-delete', 'PaymentsController@forceDelete')->name('api.payment.force-delete');




    // Notificationlog Route
    Route::get('notificationlogs', 'NotificationlogsController@index')->name('api.notificationlog.index');
    Route::get('/notificationlogs/{notificationlog}', 'NotificationlogsController@form')->name('api.notificationlog.form');
    Route::post('/notificationlogs/save', 'NotificationlogsController@post')->name('api.notificationlog.save');
    Route::post('/notificationlogs/{notificationlog}/delete', 'NotificationlogsController@delete')->name('api.notificationlog.delete');
    Route::post('/notificationlogs/{notificationlog}/restore', 'NotificationlogsController@restore')->name('api.notificationlog.restore');
    Route::post('/notificationlogs/{notificationlog}/force-delete', 'NotificationlogsController@forceDelete')->name('api.notificationlog.force-delete');


    // Notification Route
    Route::get('notifications', 'NotificationsController@index')->name('api.notification.index');
    Route::get('/notifications/{notification}', 'NotificationsController@form')->name('api.notification.form');
    Route::post('/notifications/save', 'NotificationsController@post')->name('api.notification.save');
    Route::post('/notifications/{notification}/delete', 'NotificationsController@delete')->name('api.notification.delete');
    Route::post('/notifications/{notification}/restore', 'NotificationsController@restore')->name('api.notification.restore');
    Route::post('/notifications/{notification}/force-delete', 'NotificationsController@forceDelete')->name('api.notification.force-delete');


    // Hackingactivity Route
    Route::get('hackingactivities', 'HackingactivitiesController@index')->name('api.hackingactivity.index');
    Route::get('/hackingactivities/{hackingactivity}', 'HackingactivitiesController@form')->name('api.hackingactivity.form');
    Route::post('/hackingactivities/save', 'HackingactivitiesController@post')->name('api.hackingactivity.save');
    Route::post('/hackingactivities/{hackingactivity}/delete', 'HackingactivitiesController@delete')->name('api.hackingactivity.delete');
    Route::post('/hackingactivities/{hackingactivity}/restore', 'HackingactivitiesController@restore')->name('api.hackingactivity.restore');
    Route::post('/hackingactivities/{hackingactivity}/force-delete', 'HackingactivitiesController@forceDelete')->name('api.hackingactivity.force-delete');




    // Failedjob Route
    Route::get('failedjobs', 'FailedjobsController@index')->name('api.failedjob.index');
    Route::get('/failedjobs/{failedjob}', 'FailedjobsController@form')->name('api.failedjob.form');
    Route::post('/failedjobs/save', 'FailedjobsController@post')->name('api.failedjob.save');
    Route::post('/failedjobs/{failedjob}/delete', 'FailedjobsController@delete')->name('api.failedjob.delete');
    Route::post('/failedjobs/{failedjob}/restore', 'FailedjobsController@restore')->name('api.failedjob.restore');
    Route::post('/failedjobs/{failedjob}/force-delete', 'FailedjobsController@forceDelete')->name('api.failedjob.force-delete');


    // Tempu2 Route
    Route::get('tempu2s', 'Tempu2sController@index')->name('api.tempu2.index');
    Route::get('/tempu2s/{tempu2}', 'Tempu2sController@form')->name('api.tempu2.form');
    Route::post('/tempu2s/save', 'Tempu2sController@post')->name('api.tempu2.save');
    Route::post('/tempu2s/{tempu2}/delete', 'Tempu2sController@delete')->name('api.tempu2.delete');
    Route::post('/tempu2s/{tempu2}/restore', 'Tempu2sController@restore')->name('api.tempu2.restore');
    Route::post('/tempu2s/{tempu2}/force-delete', 'Tempu2sController@forceDelete')->name('api.tempu2.force-delete');


    // Tempu Route
    Route::get('tempus', 'TempusController@index')->name('api.tempu.index');
    Route::get('/tempus/{tempu}', 'TempusController@form')->name('api.tempu.form');
    Route::post('/tempus/save', 'TempusController@post')->name('api.tempu.save');
    Route::post('/tempus/{tempu}/delete', 'TempusController@delete')->name('api.tempu.delete');
    Route::post('/tempus/{tempu}/restore', 'TempusController@restore')->name('api.tempu.restore');
    Route::post('/tempus/{tempu}/force-delete', 'TempusController@forceDelete')->name('api.tempu.force-delete');


    // Cuser Route
    Route::get('cusers', 'CusersController@index')->name('api.cuser.index');
    Route::get('/cusers/{cuser}', 'CusersController@form')->name('api.cuser.form');
    Route::post('/cusers/save', 'CusersController@post')->name('api.cuser.save');
    Route::post('/cusers/{cuser}/delete', 'CusersController@delete')->name('api.cuser.delete');
    Route::post('/cusers/{cuser}/restore', 'CusersController@restore')->name('api.cuser.restore');
    Route::post('/cusers/{cuser}/force-delete', 'CusersController@forceDelete')->name('api.cuser.force-delete');


    // Customuser Route
    Route::get('customusers', 'CustomusersController@index')->name('api.customuser.index');
    Route::get('/customusers/{customuser}', 'CustomusersController@form')->name('api.customuser.form');
    Route::post('/customusers/save','CustomusersController@post')->name('api.customuser.save');
    Route::post('/customusers/{customuser}/delete', 'CustomusersController@delete')->name('api.customuser.delete');
    Route::post('/customusers/{customuser}/restore', 'CustomusersController@restore')->name('api.customuser.restore');
    Route::post('/customusers/{customuser}/force-delete', 'CustomusersController@forceDelete')->name('api.customuser.force-delete');


    // Invoice Route
    Route::get('invoices', 'InvoicesController@index')->name('api.invoice.index');
    Route::get('/invoices/{invoice}', 'InvoicesController@form')->name('api.invoice.form');
    Route::post('/invoices/save', 'InvoicesController@post')->name('api.invoice.save');
    Route::post('/invoices/{invoice}/delete', 'InvoicesController@delete')->name('api.invoice.delete');
    Route::post('/invoices/{invoice}/restore', 'InvoicesController@restore')->name('api.invoice.restore');
    Route::post('/invoices/{invoice}/force-delete', 'InvoicesController@forceDelete')->name('api.invoice.force-delete');


    // LoginActivityLog Route
    Route::get('loginActivityLogs', 'LoginActivityLogsController@index')->name('api.loginActivityLog.index');
    Route::get('/loginActivityLogs/{loginActivityLog}', 'LoginActivityLogsController@form')->name('api.loginActivityLog.form');
    Route::post('/loginActivityLogs/save', 'LoginActivityLogsController@post')->name('api.loginActivityLog.save');
    Route::post('/loginActivityLogs/{loginActivityLog}/delete', 'LoginActivityLogsController@delete')->name('api.loginActivityLog.delete');
    Route::post('/loginActivityLogs/{loginActivityLog}/restore', 'LoginActivityLogsController@restore')->name('api.loginActivityLog.restore');
    Route::post('/loginActivityLogs/{loginActivityLog}/force-delete', 'LoginActivityLogsController@forceDelete')->name('api.loginActivityLog.force-delete');




    // Provideruser Route
    Route::get('providerusers', 'ProviderusersController@index')->name('api.provideruser.index');
    Route::get('/providerusers/{provideruser}', 'ProviderusersController@form')->name('api.provideruser.form');
    Route::post('/providerusers/save', 'ProviderusersController@post')->name('api.provideruser.save');
    Route::post('/providerusers/{provideruser}/delete', 'ProviderusersController@delete')->name('api.provideruser.delete');
    Route::post('/providerusers/{provideruser}/restore', 'ProviderusersController@restore')->name('api.provideruser.restore');
    Route::post('/providerusers/{provideruser}/force-delete', 'ProviderusersController@forceDelete')->name('api.provideruser.force-delete');


    // Customermetadatum Route
    Route::get('customermetadata', 'CustomermetadataController@index')->name('api.customermetadatum.index');
    Route::get('/customermetadata/{customermetadatum}', 'CustomermetadataController@form')->name('api.customermetadatum.form');
    Route::post('/customermetadata/save', 'CustomermetadataController@post')->name('api.customermetadatum.save');
    Route::post('/customermetadata/{customermetadatum}/delete', 'CustomermetadataController@delete')->name('api.customermetadatum.delete');
    Route::post('/customermetadata/{customermetadatum}/restore', 'CustomermetadataController@restore')->name('api.customermetadatum.restore');
    Route::post('/customermetadata/{customermetadatum}/force-delete', 'CustomermetadataController@forceDelete')->name('api.customermetadatum.force-delete');


    // Cronjob Route
    Route::get('cronjobs', 'CronjobsController@index')->name('api.cronjob.index');
    Route::get('/cronjobs/{cronjob}', 'CronjobsController@form')->name('api.cronjob.form');
    Route::post('/cronjobs/save', 'CronjobsController@post')->name('api.cronjob.save');
    Route::post('/cronjobs/{cronjob}/delete', 'CronjobsController@delete')->name('api.cronjob.delete');
    Route::post('/cronjobs/{cronjob}/restore', 'CronjobsController@restore')->name('api.cronjob.restore');
    Route::post('/cronjobs/{cronjob}/force-delete', 'CronjobsController@forceDelete')->name('api.cronjob.force-delete');


    // Payout Route
    Route::get('payouts', 'PayoutsController@index')->name('api.payout.index');
    Route::get('/payouts/{payout}', 'PayoutsController@form')->name('api.payout.form');
    Route::post('/payouts/save', 'PayoutsController@post')->name('api.payout.save');
    Route::post('/payouts/{payout}/delete', 'PayoutsController@delete')->name('api.payout.delete');
    Route::post('/payouts/{payout}/restore', 'PayoutsController@restore')->name('api.payout.restore');
    Route::post('/payouts/{payout}/force-delete', 'PayoutsController@forceDelete')->name('api.payout.force-delete');


    // Plan Route
    
    Route::get('users/{users_uuid}/plans/{plans_uuid}', 'PlansController@get')->name('api.plan.get');

    Route::post('users/{users_uuid}/plans', 'PlansController@add_plan')->name('api.plan.add_plan');

    Route::post('users/{users_uuid}/plans/{plans_uuid}', 'PlansController@edit_plan')->name('api.plan.edit_plan');

    Route::delete('users/{users_uuid}/plans/{plans_uuid}', 'PlansController@delete_plan')->name('api.plan.delete_plan');

    Route::post('/plans/{plan}/restore', 'PlansController@restore')->name('api.plan.restore');
    Route::post('/plans/{plan}/force-delete', 'PlansController@forceDelete')->name('api.plan.force-delete');


    // Postcode Route
    
    Route::get('/postcodes/{postcode}', 'PostcodesController@form')->name('api.postcode.form');
    Route::post('/postcodes/save', 'PostcodesController@post')->name('api.postcode.save');
    Route::post('/postcodes/{postcode}/delete', 'PostcodesController@delete')->name('api.postcode.delete');
    Route::post('/postcodes/{postcode}/restore', 'PostcodesController@restore')->name('api.postcode.restore');
    Route::post('/postcodes/{postcode}/force-delete', 'PostcodesController@forceDelete')->name('api.postcode.force-delete');


    // Promocode Route
    Route::get('users/{users_uuid}/promocodes', 'PromocodesController@get_all_promocodes')->name('api.promocode.get_all_promocodes');

    Route::get('users/{users_uuid}/promocodes/{promocodes_uuid}', 'PromocodesController@get')->name('api.promocode.get');

    Route::post('users/{users_uuid}/promocodes/', 'PromocodesController@add_promocode')->name('api.promocode.save');

    Route::post('users/{users_uuid}/promocodes/{promocodes_uuid}', 'PromocodesController@edit_promocode')->name('api.promocode.edit');

    Route::delete('users/{users_uuid}/promocodes/{promocodes_uuid}', 'PromocodesController@delete_promocode')->name('api.promocode.delete');




    // Providermetadatum Route





    // Promotion Route
    Route::get('promotions', 'PromotionsController@index')->name('api.promotion.index');
    Route::get('/promotions/{promotion}', 'PromotionsController@form')->name('api.promotion.form');
    Route::post('/promotions/save', 'PromotionsController@post')->name('api.promotion.save');
    Route::post('/promotions/{promotion}/delete', 'PromotionsController@delete')->name('api.promotion.delete');
    Route::post('/promotions/{promotion}/restore', 'PromotionsController@restore')->name('api.promotion.restore');
    Route::post('/promotions/{promotion}/force-delete', 'PromotionsController@forceDelete')->name('api.promotion.force-delete');


    // Providermetadatum Route
    // Route::get('providermetadata', 'ProvidermetadataController@index')->name('api.providermetadatum.index');
    // Route::get('/providermetadata/{providermetadatum}', 'ProvidermetadataController@form')->name('api.providermetadatum.form');
    // Route::post('/providermetadata/save', 'ProvidermetadataController@post')->name('api.providermetadatum.save');
    // Route::post('/providermetadata/{providermetadatum}/delete', 'ProvidermetadataController@delete')->name('api.providermetadatum.delete');
    // Route::post('/providermetadata/{providermetadatum}/restore', 'ProvidermetadataController@restore')->name('api.providermetadatum.restore');
    // Route::post('/providermetadata/{providermetadatum}/force-delete', 'ProvidermetadataController@forceDelete')->name('api.providermetadatum.force-delete');




    // Providerservicemap Route
    Route::get('providerservicemaps', 'ProviderservicemapsController@index')->name('api.providerservicemap.index');
    Route::get('/providerservicemaps/{providerservicemap}', 'ProviderservicemapsController@form')->name('api.providerservicemap.form');
    Route::post('/providerservicemaps/save', 'ProviderservicemapsController@post')->name('api.providerservicemap.save');
    Route::post('/providerservicemaps/{providerservicemap}/delete', 'ProviderservicemapsController@delete')->name('api.providerservicemap.delete');
    Route::post('/providerservicemaps/{providerservicemap}/restore', 'ProviderservicemapsController@restore')->name('api.providerservicemap.restore');
    Route::post('/providerservicemaps/{providerservicemap}/force-delete', 'ProviderservicemapsController@forceDelete')->name('api.providerservicemap.force-delete');


    // Reportedincident Route
    Route::get('reportedincidents', 'ReportedincidentsController@index')->name('api.reportedincident.index');
    Route::get('/reportedincidents/{reportedincident}', 'ReportedincidentsController@form')->name('api.reportedincident.form');
    Route::post('/reportedincidents/save', 'ReportedincidentsController@post')->name('api.reportedincident.save');

    Route::post('/reportedincidents/{reportedincident}/delete', 'ReportedincidentsController@delete')->name('api.reportedincident.delete');
    Route::post('/reportedincidents/{reportedincident}/restore', 'ReportedincidentsController@restore')->name('api.reportedincident.restore');
    Route::post('/reportedincidents/{reportedincident}/force-delete', 'ReportedincidentsController@forceDelete')->name('api.reportedincident.force-delete');


    // Service Route
    Route::get('services', 'ServicesController@index')->name('api.service.index');
    Route::get('/services/{service}', 'ServicesController@form')->name('api.service.form');
    Route::post('/services/save', 'ServicesController@post')->name('api.service.save');
    Route::post('/services/{service}/delete', 'ServicesController@delete')->name('api.service.delete');
    Route::post('/services/{service}/restore', 'ServicesController@restore')->name('api.service.restore');
    Route::post('/services/{service}/force-delete', 'ServicesController@forceDelete')->name('api.service.force-delete');


    // Servicecategory Route
    Route::get('users/{users_uuid}/servicecategories', 'ServicecategoriesController@get_all_servicecategory')->name('api.servicecategory.get_all_servicecategory');

    Route::get('users/{users_uuid}/servicecategories/{servicecategories_uuid}', 'ServicecategoriesController@get')->name('api.servicecategory.get');

    Route::post('users/{users_uuid}/servicecategories', 'ServicecategoriesController@add_servicecategory')->name('api.servicecategory.save');

    Route::post('users/{users_uuid}/servicecategories/{servicecategories_uuid}', 'ServicecategoriesController@edit_servicecategory')->name('api.servicecategory.edit');

    Route::delete('users/{users_uuid}/servicecategories/{servicecategories_uuid}', 'ServicecategoriesController@delete_servicecategory')->name('api.servicecategory.delete');

    // Route::post('/servicecategories/{servicecategory}/restore', 'ServicecategoriesController@restore')->name('api.servicecategory.restore');
    // Route::post('/servicecategories/{servicecategory}/force-delete', 'ServicecategoriesController@forceDelete')->name('api.servicecategory.force-delete');


    // Servicequestion Route
    Route::get('servicequestions', 'ServicequestionsController@index')->name('api.servicequestion.index');
    Route::get('/servicequestions/{servicequestion}', 'ServicequestionsController@form')->name('api.servicequestion.form');
    Route::post('/servicequestions/save', 'ServicequestionsController@post')->name('api.servicequestion.save');
    Route::post('/servicequestions/{servicequestion}/delete', 'ServicequestionsController@delete')->name('api.servicequestion.delete');
    Route::post('/servicequestions/{servicequestion}/restore', 'ServicequestionsController@restore')->name('api.servicequestion.restore');
    Route::post('/servicequestions/{servicequestion}/force-delete', 'ServicequestionsController@forceDelete')->name('api.servicequestion.force-delete');


    // Setting Route
    Route::get('settings', 'SettingsController@index')->name('api.setting.index');
    Route::get('/settings/{setting}', 'SettingsController@form')->name('api.setting.form');
    Route::post('/settings/save', 'SettingsController@post')->name('api.setting.save');
    Route::post('/settings/{setting}/delete', 'SettingsController@delete')->name('api.setting.delete');
    Route::post('/settings/{setting}/restore', 'SettingsController@restore')->name('api.setting.restore');
    Route::post('/settings/{setting}/force-delete', 'SettingsController@forceDelete')->name('api.setting.force-delete');


    // Cmspage Route
    Route::get('cmspages', 'CmspagesController@index')->name('api.cmspage.index');
    Route::get('/cmspages/{cmspage}', 'CmspagesController@form')->name('api.cmspage.form');
    Route::post('/cmspages/save', 'CmspagesController@post')->name('api.cmspage.save');
    Route::post('/cmspages/{cmspage}/delete', 'CmspagesController@delete')->name('api.cmspage.delete');
    Route::post('/cmspages/{cmspage}/restore', 'CmspagesController@restore')->name('api.cmspage.restore');
    Route::post('/cmspages/{cmspage}/force-delete', 'CmspagesController@forceDelete')->name('api.cmspage.force-delete');


    // Chat Route
    Route::get('chats', 'ChatsController@index')->name('api.chat.index');
    Route::get('/chats/{chat}', 'ChatsController@form')->name('api.chat.form');
    Route::post('/chats/save', 'ChatsController@post')->name('api.chat.save');
    Route::post('/chats/{chat}/delete', 'ChatsController@delete')->name('api.chat.delete');
    Route::post('/chats/{chat}/restore', 'ChatsController@restore')->name('api.chat.restore');
    Route::post('/chats/{chat}/force-delete', 'ChatsController@forceDelete')->name('api.chat.force-delete');




    // Supportticket Route
    Route::get('supporttickets', 'SupportticketsController@index')->name('api.supportticket.index');
    Route::get('/supporttickets/{supportticket}', 'SupportticketsController@form')->name('api.supportticket.form');
    Route::post('/supporttickets/save', 'SupportticketsController@post')->name('api.supportticket.save');
    Route::post('/supporttickets/{supportticket}/delete', 'SupportticketsController@delete')->name('api.supportticket.delete');
    Route::post('/supporttickets/{supportticket}/restore', 'SupportticketsController@restore')->name('api.supportticket.restore');
    Route::post('/supporttickets/{supportticket}/force-delete', 'SupportticketsController@forceDelete')->name('api.supportticket.force-delete');


    // Admin Route
    Route::get('admins', 'AdminsController@index')->name('api.admin.index');
    Route::get('/admins/{admin}', 'AdminsController@form')->name('api.admin.form');
    Route::post('/admins/save', 'AdminsController@post')->name('api.admin.save');
    Route::post('/admins/{admin}/delete', 'AdminsController@delete')->name('api.admin.delete');
    Route::post('/admins/{admin}/restore', 'AdminsController@restore')->name('api.admin.restore');
    Route::post('/admins/{admin}/force-delete', 'AdminsController@forceDelete')->name('api.admin.force-delete');


    // Supporttickethistory Route
    Route::get('supporttickethistories', 'SupporttickethistoriesController@index')->name('api.supporttickethistory.index');
    Route::get('/supporttickethistories/{supporttickethistory}', 'SupporttickethistoriesController@form')->name('api.supporttickethistory.form');
    Route::post('/supporttickethistories/save', 'SupporttickethistoriesController@post')->name('api.supporttickethistory.save');
    Route::post('/supporttickethistories/{supporttickethistory}/delete', 'SupporttickethistoriesController@delete')->name('api.supporttickethistory.delete');
    Route::post('/supporttickethistories/{supporttickethistory}/restore', 'SupporttickethistoriesController@restore')->name('api.supporttickethistory.restore');
    Route::post('/supporttickethistories/{supporttickethistory}/force-delete', 'SupporttickethistoriesController@forceDelete')->name('api.supporttickethistory.force-delete');


    // Adminactivitylog Route
    Route::get('adminactivitylogs', 'AdminactivitylogsController@index')->name('api.adminactivitylog.index');
    Route::get('/adminactivitylogs/{adminactivitylog}', 'AdminactivitylogsController@form')->name('api.adminactivitylog.form');
    Route::post('/adminactivitylogs/save', 'AdminactivitylogsController@post')->name('api.adminactivitylog.save');
    Route::post('/adminactivitylogs/{adminactivitylog}/delete', 'AdminactivitylogsController@delete')->name('api.adminactivitylog.delete');
    Route::post('/adminactivitylogs/{adminactivitylog}/restore', 'AdminactivitylogsController@restore')->name('api.adminactivitylog.restore');
    Route::post('/adminactivitylogs/{adminactivitylog}/force-delete', 'AdminactivitylogsController@forceDelete')->name('api.adminactivitylog.force-delete');


    // Annoucement Route
    Route::get('annoucements', 'AnnoucementsController@index')->name('api.annoucement.index');
    Route::get('/annoucements/{annoucement}', 'AnnoucementsController@form')->name('api.annoucement.form');
    Route::post('/annoucements/save', 'AnnoucementsController@post')->name('api.annoucement.save');
    Route::post('/annoucements/{annoucement}/delete', 'AnnoucementsController@delete')->name('api.annoucement.delete');
    Route::post('/annoucements/{annoucement}/restore', 'AnnoucementsController@restore')->name('api.annoucement.restore');
    Route::post('/annoucements/{annoucement}/force-delete', 'AnnoucementsController@forceDelete')->name('api.annoucement.force-delete');


    // Useractivitylog Route
    Route::get('useractivitylogs', 'UseractivitylogsController@index')->name('api.useractivitylog.index');
    Route::get('/useractivitylogs/{useractivitylog}', 'UseractivitylogsController@form')->name('api.useractivitylog.form');
    Route::post('/useractivitylogs/save', 'UseractivitylogsController@post')->name('api.useractivitylog.save');
    Route::post('/useractivitylogs/{useractivitylog}/delete', 'UseractivitylogsController@delete')->name('api.useractivitylog.delete');
    Route::post('/useractivitylogs/{useractivitylog}/restore', 'UseractivitylogsController@restore')->name('api.useractivitylog.restore');
    Route::post('/useractivitylogs/{useractivitylog}/force-delete', 'UseractivitylogsController@forceDelete')->name('api.useractivitylog.force-delete');


    // Apilog Route
    Route::get('apilogs', 'ApilogsController@index')->name('api.apilog.index');
    Route::get('/apilogs/{apilog}', 'ApilogsController@form')->name('api.apilog.form');
    Route::post('/apilogs/save', 'ApilogsController@post')->name('api.apilog.save');
    Route::post('/apilogs/{apilog}/delete', 'ApilogsController@delete')->name('api.apilog.delete');
    Route::post('/apilogs/{apilog}/restore', 'ApilogsController@restore')->name('api.apilog.restore');
    Route::post('/apilogs/{apilog}/force-delete', 'ApilogsController@forceDelete')->name('api.apilog.force-delete');


    // Useraddress Route
    Route::get('useraddresses', 'UseraddressesController@index')->name('api.useraddress.index')->middleware(['scopes:customer']);
    Route::get('/useraddresses/{useraddress}', 'UseraddressesController@form')->name('api.useraddress.form');
    Route::post('/useraddresses/save', 'UseraddressesController@post')->name('api.useraddress.save');
    Route::post('/useraddresses/{useraddress}/delete', 'UseraddressesController@delete')->name('api.useraddress.delete');
    Route::post('/useraddresses/{useraddress}/restore', 'UseraddressesController@restore')->name('api.useraddress.restore');
    Route::post('/useraddresses/{useraddress}/force-delete', 'UseraddressesController@forceDelete')->name('api.useraddress.force-delete');
    Route::get('/users/{users_uuid}/addresses', 'UseraddressesController@get')->name('api.useraddress.get');
    Route::get('/providers/{users_uuid}/addresses', 'UseraddressesController@get')->name('api.useraddress.get');


    // Badge Route

    Route::get('users/{users_uuid}/badges', 'BadgesController@get_all_badge')->name('api.badge.get_all_badge');

    Route::get('users/{users_uuid}/badges/{badges_uuid}', 'BadgesController@get')->name('api.badge.get');

    Route::post('users/{users_uuid}/badges', 'BadgesController@add_badge')->name('api.badge.save');

    Route::post('users/{user_uuid}/badges/{badges_uuid}', 'BadgesController@edit_badge')->name('api.badge.save');

    Route::delete('users/{users_uuid}/badges/{badges_uuid}', 'BadgesController@delete_badge')->name('api.badge.delete_badge') ;


    Route::post('/badges/{badge}/restore', 'BadgesController@restore')->name('api.badge.restore');
    Route::post('/badges/{badge}/force-delete', 'BadgesController@forceDelete')->name('api.badge.force-delete');

    // Route::get('badges', 'BadgesController@index')->name('api.badge.index');
    // Route::get('/badges/{badge}', 'BadgesController@form')->name('api.badge.form');
    // Route::post('/badges/save', 'BadgesController@post')->name('api.badge.save')->middleware(['scopes:admin']);
    // Route::post('/badges/{badge}/delete', 'BadgesController@delete')->name('api.badge.delete');
    // Route::post('/badges/{badge}/restore', 'BadgesController@restore')->name('api.badge.restore');
    // Route::post('/badges/{badge}/force-delete', 'BadgesController@forceDelete')->name('api.badge.force-delete');



    // Userbadge Route
    Route::get('userbadges', 'UserbadgesController@index')->name('api.userbadge.index');
    Route::get('/userbadges/{userbadge}', 'UserbadgesController@form')->name('api.userbadge.form');
    Route::post('/userbadges/save', 'UserbadgesController@post')->name('api.userbadge.save');
    Route::post('/userbadges/{userbadge}/delete', 'UserbadgesController@delete')->name('api.userbadge.delete');
    Route::post('/userbadges/{userbadge}/restore', 'UserbadgesController@restore')->name('api.userbadge.restore');
    Route::post('/userbadges/{userbadge}/force-delete', 'UserbadgesController@forceDelete')->name('api.userbadge.force-delete');
    Route::get('/users/{users_uuid}/userbadges', 'UserbadgesController@get')->name('api.userbadge.get');


    // Usernotification Route
    Route::get('usernotifications', 'UsernotificationsController@index')->name('api.usernotification.index');
    Route::get('/usernotifications/{usernotification}', 'UsernotificationsController@form')->name('api.usernotification.form');
    Route::post('/usernotifications/save', 'UsernotificationsController@post')->name('api.usernotification.save');
    Route::post('/usernotifications/{usernotification}/delete', 'UsernotificationsController@delete')->name('api.usernotification.delete');
    Route::post('/usernotifications/{usernotification}/restore', 'UsernotificationsController@restore')->name('api.usernotification.restore');
    Route::post('/usernotifications/{usernotification}/force-delete', 'UsernotificationsController@forceDelete')->name('api.usernotification.force-delete');


    // Bookingactivitylog Route
    Route::get('bookingactivitylogs', 'BookingactivitylogsController@index')->name('api.bookingactivitylog.index');
    Route::get('/bookingactivitylogs/{bookingactivitylog}', 'BookingactivitylogsController@form')->name('api.bookingactivitylog.form');
    Route::post('/bookingactivitylogs/save', 'BookingactivitylogsController@post')->name('api.bookingactivitylog.save');
    Route::post('/bookingactivitylogs/{bookingactivitylog}/delete', 'BookingactivitylogsController@delete')->name('api.bookingactivitylog.delete');
    Route::post('/bookingactivitylogs/{bookingactivitylog}/restore', 'BookingactivitylogsController@restore')->name('api.bookingactivitylog.restore');
    Route::post('/bookingactivitylogs/{bookingactivitylog}/force-delete', 'BookingactivitylogsController@forceDelete')->name('api.bookingactivitylog.force-delete');


    // Userreview Route
    Route::get('userreviews', 'UserreviewsController@index')->name('api.userreview.index');
    Route::get('/userreviews/{userreview}', 'UserreviewsController@form')->name('api.userreview.form');
    Route::post('/userreviews/save', 'UserreviewsController@post')->name('api.userreview.save');
    Route::post('/userreviews/{userreview}/delete', 'UserreviewsController@delete')->name('api.userreview.delete');
    Route::post('/userreviews/{userreview}/restore', 'UserreviewsController@restore')->name('api.userreview.restore');
    Route::post('/userreviews/{userreview}/force-delete', 'UserreviewsController@forceDelete')->name('api.userreview.force-delete');


    // Bookingstatus Route
    Route::get('bookingstatuses', 'BookingstatusesController@index')->name('api.bookingstatus.index');
    Route::get('/bookingstatuses/{bookingstatus}', 'BookingstatusesController@form')->name('api.bookingstatus.form');
    Route::post('/bookingstatuses/save', 'BookingstatusesController@post')->name('api.bookingstatus.save');
    Route::post('/bookingstatuses/{bookingstatus}/delete', 'BookingstatusesController@delete')->name('api.bookingstatus.delete');
    Route::post('/bookingstatuses/{bookingstatus}/restore', 'BookingstatusesController@restore')->name('api.bookingstatus.restore');
    Route::post('/bookingstatuses/{bookingstatus}/force-delete', 'BookingstatusesController@forceDelete')->name('api.bookingstatus.force-delete');


    // Bookingservice Route
    Route::get('bookingservices', 'BookingservicesController@index')->name('api.bookingservice.index');
    Route::get('/bookingservices/{bookingservice}', 'BookingservicesController@form')->name('api.bookingservice.form');
    Route::post('/bookingservices/save', 'BookingservicesController@post')->name('api.bookingservice.save');
    Route::post('/bookingservices/{bookingservice}/delete', 'BookingservicesController@delete')->name('api.bookingservice.delete');
    Route::post('/bookingservices/{bookingservice}/restore', 'BookingservicesController@restore')->name('api.bookingservice.restore');
    Route::post('/bookingservices/{bookingservice}/force-delete', 'BookingservicesController@forceDelete')->name('api.bookingservice.force-delete');


    // Bookingrequestprovider Route
    Route::get('bookingrequestproviders', 'BookingrequestprovidersController@index')->name('api.bookingrequestprovider.index');
    Route::get('/bookingrequestproviders/{bookingrequestprovider}', 'BookingrequestprovidersController@form')->name('api.bookingrequestprovider.form');
    Route::post('/bookingrequestproviders/save', 'BookingrequestprovidersController@post')->name('api.bookingrequestprovider.save');
    Route::post('/bookingrequestproviders/{bookingrequestprovider}/delete', 'BookingrequestprovidersController@delete')->name('api.bookingrequestprovider.delete');
    Route::post('/bookingrequestproviders/{bookingrequestprovider}/restore', 'BookingrequestprovidersController@restore')->name('api.bookingrequestprovider.restore');

    Route::post('/bookingrequestproviders/{bookingrequestprovider}/force_delete', 'BookingrequestprovidersController@forceDelete')->name('api.bookingrequestprovider.force-delete');


    // Bookingrecurringpattern Route
    Route::get('bookingrecurringpatterns', 'BookingrecurringpatternsController@index')->name('api.bookingrecurringpattern.index');
    Route::get('/bookingrecurringpatterns/{bookingrecurringpattern}', 'BookingrecurringpatternsController@form')->name('api.bookingrecurringpattern.form');
    Route::post('/bookingrecurringpatterns/save', 'BookingrecurringpatternsController@post')->name('api.bookingrecurringpattern.save');
    Route::post('/bookingrecurringpatterns/{bookingrecurringpattern}/delete', 'BookingrecurringpatternsController@delete')->name('api.bookingrecurringpattern.delete');
    Route::post('/bookingrecurringpatterns/{bookingrecurringpattern}/restore', 'BookingrecurringpatternsController@restore')->name('api.bookingrecurringpattern.restore');
    Route::post('/bookingrecurringpatterns/{bookingrecurringpattern}/force-delete', 'BookingrecurringpatternsController@forceDelete')->name('api.bookingrecurringpattern.force-delete');


    // Bookingquestion Route
    Route::get('bookingquestions', 'BookingquestionsController@index')->name('api.bookingquestion.index');
    Route::get('/bookingquestions/{bookingquestion}', 'BookingquestionsController@form')->name('api.bookingquestion.form');
    Route::post('/bookingquestions/save', 'BookingquestionsController@post')->name('api.bookingquestion.save');
    Route::post('/bookingquestions/{bookingquestion}/delete', 'BookingquestionsController@delete')->name('api.bookingquestion.delete');
    Route::post('/bookingquestions/{bookingquestion}/restore', 'BookingquestionsController@restore')->name('api.bookingquestion.restore');
    Route::post('/bookingquestions/{bookingquestion}/force-delete', 'BookingquestionsController@forceDelete')->name('api.bookingquestion.force-delete');






    // Bookingchange Route
    Route::get('bookingchanges', 'BookingchangesController@index')->name('api.bookingchange.index');
    Route::get('/bookingchanges/{bookingchange}', 'BookingchangesController@form')->name('api.bookingchange.form');
    Route::post('/bookingchanges/save', 'BookingchangesController@post')->name('api.bookingchange.save');
    Route::post('/bookingchanges/{bookingchange}/delete', 'BookingchangesController@delete')->name('api.bookingchange.delete');
    Route::post('/bookingchanges/{bookingchange}/restore', 'BookingchangesController@restore')->name('api.bookingchange.restore');
    Route::post('/bookingchanges/{bookingchange}/force-delete', 'BookingchangesController@forceDelete')->name('api.bookingchange.force-delete');


    // Bookingaddress Route
    Route::get('bookingaddresses', 'BookingaddressesController@index')->name('api.bookingaddress.index');
    Route::get('/bookingaddresses/{bookingaddress}', 'BookingaddressesController@form')->name('api.bookingaddress.form');
    Route::post('/bookingaddresses/save', 'BookingaddressesController@post')->name('api.bookingaddress.save');
    Route::post('/bookingaddresses/{bookingaddress}/delete', 'BookingaddressesController@delete')->name('api.bookingaddress.delete');
    Route::post('/bookingaddresses/{bookingaddress}/restore', 'BookingaddressesController@restore')->name('api.bookingaddress.restore');
    Route::post('/bookingaddresses/{bookingaddress}/force-delete', 'BookingaddressesController@forceDelete')->name('api.bookingaddress.force-delete');


    // Booking Route
    Route::get('users/{uuid}/bookings/{uuid1}', 'BookingsController@index')->name('api.booking.index');

    // Route::get('bookings', 'BookingsController@index')->name('api.booking.index');

    Route::get('/bookings/{booking}', 'BookingsController@form')->name('api.booking.form');
    Route::post('/bookings/save', 'BookingsController@post')->name('api.booking.save');
    Route::post('/bookings/{booking}/delete', 'BookingsController@delete')->name('api.booking.delete');
    Route::post('/bookings/{booking}/restore', 'BookingsController@restore')->name('api.booking.restore');
    Route::post('/bookings/{booking}/force-delete', 'BookingsController@forceDelete')->name('api.booking.force-delete');

});

//provider api
Route::namespace('Backend\API')->prefix('v1/provider')->group(function () {

     // Providermetadata Route

    Route::get('providermetadata', 'ProvidermetadataController@get_all_providermetadata')->name('api.providermetadatum.get_all_providermetadata');

    Route::get('providermetadata/{providermetadata_uuid}', 'ProvidermetadataController@get')->name('api.providermetadatum.get');

    Route::post('providerbankdata', 'ProvidermetadataController@add_bankdata')->name('api.providermetadatum.save');

    Route::post('providermetadata/{providermetadata_uuid}', 'ProvidermetadataController@edit_bankdata')->name('api.providermetadata.edit');

     Route::delete('providermetadata/{providermetadata_uuid}', 'ProvidermetadataController@delete_providermetadata')->name('api.providermetadata.delete');

    // Providerportfolio Route
     Route::post('providerportfolios', 'ProviderportfoliosController@add_portfolio')->name('api.providerportfolio.save');
     Route::post('providerportfolios/{providerportfolios_uuid}', 'ProviderportfoliosController@edit_portfolio')->name('api.providerportfolio.edit');

     Route::get('providerportfolios', 'ProviderportfoliosController@get_all_providerportfolios')->name('api.providerportfolio.get_all_providermetadata');

     Route::get('providerportfolios/{providerportfolios_uuid}', 'ProviderportfoliosController@get')->name('api.providerportfolio.get');

     Route::delete('providerportfolios/{providerportfolios_uuid}', 'ProviderportfoliosController@delete_providerportfolios')->name('api.providerportfolio.delete');

    // Providerportfolio postcodes


    Route::post('postcodes', 'PostcodesController@add_postcodes')->name('api.postcodes.save');
    Route::post('postcodes/{postcodes_uuid}', 'PostcodesController@edit_postcodes')->name('api.postcodes.edit');
    Route::get('postcodes', 'PostcodesController@get_all_postcodes')->name('api.postcodes.get_all_postcodes');
     Route::get('postcodes/{postcodes_uuid}', 'PostcodesController@get')->name('api.postcodes.get');
     Route::delete('postcodes/{postcodes_uuid}', 'PostcodesController@delete_postcodes')->name('api.postcodes.delete');


});
