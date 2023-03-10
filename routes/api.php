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





Route::namespace('Backend\API')->prefix('v1/public')->group(function () {
    Route::get('postcodes', 'PostcodesController@index')->name('api.postcode.index');
    Route::get('getallservicecategories', 'ServicecategoryController@GetAllCategories')->name('getallservicecategories');
    Route::get('plans', 'PlansController@get_all_plan')->name('api.plan.get_all_plan');
    Route::post('providerdetails', 'BookingController@providerdetails')->name('providerdetails');
    Route::get('search_postcode', 'PostcodesController@search_postcode')->name('search_postcode');
    Route::get('getallcategorieswithservice', 'ServicecategoryController@getCategorywithservices')->name('getallcategorieswithservice');
});

Route::namespace('Backend\API')->prefix('v1/customer')->group(function(){
    Route::get('getallprovider', 'CustomerusersController@getallprovider')->name('api.Customeruser.getallprovider');//->middleware(['scope:customer']);
	Route::get('getallservices', 'ServiceController@index')->name('api.Service.index');//->middleware(['scope:customer']);
    Route::get('getaddress', 'UseraddressController@getaddress')->name('api.Useraddress.getaddress')->middleware('auth:api');
	Route::get('getservicequestions/{uuid}', 'ServicequestionController@getservicequestions')->name('api.Servicequestion.getservicequestions');
    Route::any('geserviceprice', 'ServiceController@geserviceprice')->name('geserviceprice');
});

Route::namespace('Backend\API')->prefix('v1/payments')->group(function(){
    Route::post('stripe/initialization', 'PaymentsController@initialiseStripe')->name('api.payments.stripe.initialization')->middleware(['auth:api']);
    Route::post('stripe/sessions', 'PaymentsController@createStripeSession')->name('api.payments.stripe.session')->middleware(['auth:api']);
    Route::get('stripe/cards', 'PaymentsController@retrieveStripeCard')->name('api.payments.stripe.cards')->middleware(['auth:api']);
    Route::post('stripe/cards', 'PaymentsController@addStripeCard')->name('api.payments.stripe.cards')->middleware(['auth:api']);
    Route::post('stripe/paymentmethods/intents', 'PaymentsController@createStripePaymentMethodIntent')->name('api.payments.stripe.payment_methods.intents')->middleware(['auth:api']);
    Route::post('stripe/account-links', 'PaymentsController@createStripeAccountLink')->name('api.payments.stripe.account_link')->middleware(['auth:api', 'scope:provider']);
    Route::post('stripe/accounts/{user}/verification', 'PaymentsController@verifyStripeAccount')->name('api.payments.stripe.accounts.verify')->middleware(['auth:api', 'scope:provider']);
    Route::post('stripe/accounts/{user}/login-links', 'PaymentsController@getAccountLoginLink')->name('api.payments.stripe.accounts.verify')->middleware(['auth:api', 'scope:provider']);
    Route::get('stripe/accounts/{user}/balances', 'PaymentsController@getStripeAccountBalance')->name('api.payments.stripe.account.balance')->middleware(['auth:api', 'scope:provider']);
});

// for passport
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('userexist', 'AuthController@UserExist')->name('userexist');
    Route::post('updatetoken', 'AuthController@UpdateToken')->name('updatetoken');
    Route::get('logout', 'AuthController@logout')->middleware(['auth:api']);
    Route::get('email/verify/{id}', 'VerificationApiController@verify')->name('verificationapi.verify');
});


// for email verify
Route::get('email/verify/{id}', 'VerificationApiController@verify')->name('verificationapi.verify');
Route::get('email/resend', 'VerificationApiController@resend')->name('verificationapi.resend');

Route::namespace('Auth')->prefix('auth')->group(function(){
    Route::post('forgetpassword', 'ResetPasswordController@forgetpassword')->name('forgetpassword');
    Route::post('verifytoken', 'ResetPasswordController@verifytoken');
    Route::post('reset', 'ResetPasswordController@reset');

});

Route::middleware(['auth:api', 'role:admin'])->namespace('Backend\API')->prefix('v1/admin')->group(function () {
   // Admin Route
   Route::post('addpaymentsettings', 'SettingController@addpaymentsettings')->name('api.Setting.addpaymentsettings')->middleware(['scope:admin']);
    Route::post('addsmssettings', 'SettingController@addsmssettings')->name('api.Setting.addsmssettings')->middleware(['scope:admin']);
    Route::post('addemailsettings', 'SettingController@addemailsettings')->name('api.Setting.addemailsettings')->middleware(['scope:admin']);
    Route::post('addfirebasesettings', 'SettingController@addfirebasesettings')->name('api.Setting.addfirebasesettings')->middleware(['scope:admin']);
    Route::get('getsettings', 'SettingController@getsettings')->name('api.Setting.getsettings')->middleware(['scope:admin']);

});
Route::post('getdashboard', 'HomeController@dashboard')->name('api.home.getdashboard')->middleware(['auth:api'])->middleware(['scope:customer']);

Route::middleware(['auth:api','scope:admin,customer,provider'])->namespace('Backend\API')->prefix('v1')->group(function () {
    Route::patch('bookings/{booking}', 'BookingController@updateBooking')->name('update_booking')->middleware(['can:update,booking']);
    Route::patch('bookings/{booking}/dates/{recurring_date}', 'BookingController@updateRecurredBooking')->name('update_recurred_booking')->middleware(['can:update,booking']);
});

Route::middleware(['auth:api','scope:customer,provider'])->namespace('Backend\API')->prefix('v1')->group(function () {
    Route::get('/bookings/{booking}', 'BookingController@getbookingdetails')->name('getbookingdetails');
    Route::get('bookings/{booking}/dates/{recurring_date}', 'BookingController@getbookingdetails')->name('getrecurredbookingdetails');
    Route::get('/bookings', 'BookingJobsController@listAllJobs');
    Route::get('/allstatus', 'BookingController@listAllStatus');
    Route::post('addreview/{id}', 'UserreviewController@addreview')->name('addreview');
    Route::post('chats/{bookingid}', 'ChatsController@addmessage')->name('addmessage');
    Route::post('getchat/{bookingid}', 'ChatsController@getchat')->name('getchat');
    Route::get('chats/list/{type}', 'ChatsController@list')->name('chatlist');
    Route::post('profilepicture', 'CustomerusersController@profilepicture')->name('profilepicture');
    Route::get('profile_view','CustomerusersController@profile_view')->name('api.Customeruser.profile_view');
    
    Route::get('/notifications', 'NotificationController@getNotifications')->name('api.notifications.list');
    Route::post('/notifications', 'NotificationController@updateNotifications')->name('api.notifications.update');

    //top notification
    Route::get('/user-notifications', 'NotificationLogsController@getNotifications')->name('api.user-notifications');
    // trying to reduce load
    
    Route::get('/provider-notifications', 'NotificationLogsController@getNotifications')->name('api.provider-notifications');

    Route::post('update-push-notification-logs', 'NotificationLogsController@updatePushNotificationLog')->name('api.push-notifcation-logs.update');

    Route::put('users', 'UserController@updateUser')->name('api.update-user');
});

Route::middleware(['auth:api', 'role:customer'])->namespace('Backend\API')->prefix('v1/customer')->group(function () {
    
    // Customeruser Route
   
    Route::get('alternate_date/{booking_uuid}', 'BookingController@get_alternate_date')->name('customer_get_alternate_date')->middleware(['scope:customer']);
    Route::post('add_alternate_date', 'BookingController@add_alternate_date')->name('add_alternate_date')->middleware(['scope:customer']);
    Route::delete('delete_alternate_date/{uuid}', 'BookingController@delete_alternate_date')->name('delete_alternate_date')->middleware(['scope:customer']);
    Route::patch('edit_alternate_date/{uuid}', 'BookingController@edit_alternate_date')->name('edit_alternate_date')->middleware(['scope:customer']);

    Route::post('promocode_discount', 'BookingController@promocode_discount')->name('promocode_discount')->middleware(['scope:customer']);
    Route::post('bookings', 'BookingController@add_booking')->name('add_booking')->middleware(['scope:customer']);

    Route::get('/bookings/{bookingId}/times', 'BookingTimesController@listBookingTimesByBookingId')
        ->name('api.bookings.times')
        ->middleware('scope:customer');

    Route::post('add_multipal_question', 'BookingquestionsController@add_multipal_question')->name('add_multipal_question')->middleware(['scope:customer']);
    Route::post('add_multipal_service', 'BookingserviceController@add_multipal_service')->name('add_multipal_service')->middleware(['scope:customer']);
    Route::get('getcleanardata/{uuid}', 'UserreviewController@getcleanardata')->name('api.Userreview.getcleanardata')->middleware(['scope:customer']);

    Route::get('profile_view', 'CustomerusersController@profile_view')->name('api.Customeruser.profile_view')->middleware(['scope:customer']);
    Route::get('getallusers', 'CustomerusersController@index')->name('api.Customeruser.index')->middleware(['scope:customer']);
   
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
    Route::post('delete_address', 'UseraddressController@delete')->name('api.Useraddress.delete_address')->middleware(['scope:customer']);
    

    Route::patch('editaddress/{uuid}', 'UseraddressController@editaddress')->name('api.Useraddress.editaddress')->middleware(['scope:customer']);

   

   
    
    Route::get('getallbookingdetails', 'BookingController@getallbookingdetails')->name('api.Booking.getallbookingdetails')->middleware(['scope:customer']);

    Route::get('getpendingbookingdetails', 'BookingController@getpendingbookingdetails')->name('api.Booking.getpendingbookingdetails')->middleware(['scope:customer']);
    Route::get('getpastbookingdetails', 'BookingController@getpastbookingdetails')->name('api.Booking.getpastbookingdetails')->middleware(['scope:customer']);

    Route::get('getfuturebookingdetails', 'BookingController@getfuturebookingdetails')->name('api.Booking.getfuturebookingdetails')->middleware(['scope:customer']);

    Route::get('getpastbookingdetails', 'BookingController@getpastbookingdetails')->name('api.Booking.getpastbookingdetails')->middleware(['scope:customer']);

    Route::get('getpaymenthistory', 'PaymentController@getpaymenthistory')->name('api.Payment.getpaymenthistory')->middleware(['scope:customer']);

    Route::get('getpaymentsettings', 'CustomermetadataController@getpaymentsettings')->name('api.Customermetadata.getpaymentsettings')->middleware(['scope:customer']);

    Route::get('getuserreviewdata/{uuid}',
     'UserreviewController@getuserreviewdata')->name('api.Userreview.getuserreviewdata')->middleware(['scope:customer']);

    
    Route::get('getratingreview', 'UserreviewController@getratingreview')->name('api.Userreview.getratingreview')->middleware(['scope:customer']);

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

 Route::middleware(['auth:api', 'role:provider'])->namespace('Backend\API')->prefix('v1/provider')->group(function(){
    // provider Route
    
    Route::get('getdashboardstatistics', 'BookingController@getdashboardstatistics')->middleware(['scope:provider']);


    Route::post('addworking_hours', 'Working_hoursController@addworking_hours')->name('api.Working_hours.addworking_hours')->middleware(['scope:provider']);
    Route::get('getworking_hours', 'Working_hoursController@getworking_hours')->name('api.Working_hours.getworking_hours')->middleware(['scope:provider']);
    Route::post('addproviderpostcode', 'PostcodesController@addproviderpostcode')->name('addproviderpostcode')->middleware(['scope:provider']);
    Route::get('getproviderpostcode', 'PostcodesController@getproviderpostcode')->name('getproviderpostcode')->middleware(['scope:provider']);
    Route::post('deleteproviderpostcode', 'PostcodesController@deleteproviderpostcode')->name('deleteproviderpostcode')->middleware(['scope:provider']);
    
     //check profile data
     Route::get('checkprofilecompleted', 'CustomerusersController@CheckProfileCompleted')->name('checkprofilecompleted');
     Route::get('checkproviderstripeverified', 'CustomerusersController@checkproviderstripeverified')->name('checkproviderstripeverified');

     Route::get('getbadges', 'CustomerusersController@getBadges')->name('getbadges')->middleware(['scope:provider']);

    
     Route::get('getservicebyprovider/{pid}', 'BookingController@GetServiceByProvider')->middleware(['scope:provider']);
     Route::get('getservicebyprovider/{userId}/categories/{categoryId}', 'BookingController@getProviderServicesByCategory')->middleware(['scope:provider']);

    Route::get('getappointment/{uuid}', 'BookingController@provider_getappointment')->name('api.Booking.provider_getappointment')->middleware(['scope:provider']);
    Route::patch('change_booking_status/{uuid}', 'BookingController@change_booking_status')->name('api.Booking.change_booking_status')->middleware(['scope:provider']);

    Route::get('getallusers', 'CustomerusersController@index')->name('api.Customeruser.index')->middleware(['scope:provider']);
     //rakesh api

    Route::get('alternate_date/{booking_uuid}', 'BookingController@get_alternate_date')->name('provider_get_alternate_date')->middleware(['scope:provider']);
    Route::patch('select_alternate_date/{uuid}', 'BookingController@provider_select_alternate_date')->name('provider_select_alternate_date')->middleware(['scope:provider']);
    Route::patch('cancelbooking/{uuid}', 'BookingController@provider_cancelbooking')->name('api.Booking.cancelbooking')->middleware(['scope:provider']);
    Route::get('promocode_discount', 'BookingController@promocode_discount')->name('promocode_discount')->middleware(['scope:provider']);
   // Route::post('add_booking', 'BookingController@add_booking')->name('add_booking')->middleware(['scope:provider']);
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

     Route::patch('change_password', 'CustomerusersController@change_password')->name('api.Customeruser.change_password')->middleware(['scope:provider']);

     Route::patch('profile_update', 'CustomerusersController@profile_update')->name('api.Customeruser.profile_update')->middleware(['scope:provider']);



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
   
    Route::post('getallcalendardetails', 'BookingController@getallcalendardetails')->name('api.Booking.getallcalendardetails')->middleware(['scope:provider']);
    Route::get('getpaymenthistory', 'PaymentController@getpaymenthistory')->name('api.Payment.getpaymenthistory')->middleware(['scope:provider']);
     Route::get('getdashboard', 'AnnoucementController@getdashboard')->name('api.Annoucement.getdashboard')->middleware(['scope:provider']);

     Route::get('getuserreviewdata/{uuid}', 'UserreviewController@getuserreviewdata')->name('api.Userreview.getuserreviewdata')->middleware(['scope:provider']);
     Route::post('addproviderreview/{uuid}', 'UserreviewController@addproviderreview')->name('api.Userreview.addproviderreview')->middleware(['scope:provider']);
     Route::get('getratingreview', 'UserreviewController@getratingreview')->name('api.Userreview.getratingreview')->middleware(['scope:provider']);


 });

 
 Route::middleware(['auth:api', 'role:public'])->namespace('Backend\API')->prefix('v1/public')->group(function () {
    // Customeruser Route
    Route::get('getallusers', 'CustomerusersController@index')->name('api.Customeruser.index')->middleware(['scope:public']);

 });

# save fcm token forcefully on mobile login
Route::middleware(['auth:api'])->prefix('v1/auth')->group(function () {
    Route::post('/device-token', 'AuthController@saveDeviceToken')->name('api.auth.device-token');
});

// ADMIN API
Route::middleware(['auth:api','scope:admin', 'role:admin'])->namespace('Backend\API')->prefix('v1/backend')->group(function () {
    //ROUTES
    // Bookings
    Route::get('/bookings', 'BookingJobsController@getAllBookings')->name('api.admin:bookings.index');;
    
    Route::get('/bookings/{booking}', 'BookingController@getbookingdetails')->name('admin.getbookingdetails');
	Route::get('bookings/{booking}/dates/{recurring_date}', 'BookingController@getbookingdetails')->name('admin.getrecurredbookingdetails');
	// TODO: need to discuss
    Route::patch('bookings/{booking}', 'BookingController@updateBooking')->name('api.admin.bookings.update');
   Route::patch('bookings/{booking}/dates/{recurring_date}', 'BookingController@updateRecurredBooking')->name('api.admin.bookings.update-recurring');

    Route::get('/allstatus', 'BookingController@listAllStatus');
	Route::post('chat-details/{bookingid}', 'ChatsController@getchat')->name('chat-details');
      
    // user
    Route::get('users', 'CustomerusersController@index')->name('api.admin.users.index');
    Route::post('profilepicture', 'CustomerusersController@profilepicture')
    ->name('profilepicture');

    Route::post('users/{id}/update-status', 'UserController@updateSatus')
        ->name('api.admin.user.update-status');
    Route::get('user/status-list', 'UserController@getAllStatus')->name('api.admin.users.status-list');

    // user profile
    Route::get('profile', 'CustomerusersController@profile_view')->name('api.admin.users.profile');
    Route::patch('profile', 'CustomerusersController@profile_update')->name('api.admin.users.profile-update');
    Route::patch('change-password', 'CustomerusersController@change_password')->name('api.admin.users.change-password');

     Route::get('user-reviews', 'CustomerusersController@getReviews')->name('api.admin.users.reviews');

    // OnceBookingAlternateDate Route
   /* Route::get('onceBookingAlternateDates', 'OnceBookingAlternateDatesController@index')->name('api.onceBookingAlternateDate.index');
    Route::get('/onceBookingAlternateDates/{onceBookingAlternateDate}', 'OnceBookingAlternateDatesController@form')->name('api.onceBookingAlternateDate.form');
    Route::post('/onceBookingAlternateDates/save', 'OnceBookingAlternateDatesController@post')->name('api.onceBookingAlternateDate.save');
    Route::post('/onceBookingAlternateDates/{onceBookingAlternateDate}/delete', 'OnceBookingAlternateDatesController@delete')->name('api.onceBookingAlternateDate.delete');
    Route::post('/onceBookingAlternateDates/{onceBookingAlternateDate}/restore', 'OnceBookingAlternateDatesController@restore')->name('api.onceBookingAlternateDate.restore');
    Route::post('/onceBookingAlternateDates/{onceBookingAlternateDate}/force-delete', 'OnceBookingAlternateDatesController@forceDelete')->name('api.onceBookingAlternateDate.force-delete');

    // Payment Route
    Route::get('payments', 'PaymentsController@index')->name('api.payment.index');
    Route::get('/payments/{payment}', 'PaymentsController@form')->name('api.payment.form');
    Route::post('/payments/save', 'PaymentsController@post')->name('api.payment.save');
    Route::post('/payments/{payment}/delete', 'PaymentsController@delete')->name('api.payment.delete');
    Route::post('/payments/{payment}/restore', 'PaymentsController@restore')->name('api.payment.restore');
    Route::post('/payments/{payment}/force-delete', 'PaymentsController@forceDelete')->name('api.payment.force-delete');

    // Notification Route
    Route::get('notifications', 'NotificationsController@index')->name('api.notification.index');
    Route::get('/notifications/{notification}', 'NotificationsController@form')->name('api.notification.form');
    Route::post('/notifications/save', 'NotificationsController@post')->name('api.notification.save');
    Route::post('/notifications/{notification}/delete', 'NotificationsController@delete')->name('api.notification.delete');
    Route::post('/notifications/{notification}/restore', 'NotificationsController@restore')->name('api.notification.restore');
    Route::post('/notifications/{notification}/force-delete', 'NotificationsController@forceDelete')->name('api.notification.force-delete');


    // Customermetadatum Route
    Route::get('customermetadata', 'CustomermetadataController@index')->name('api.customermetadatum.index');
    Route::get('/customermetadata/{customermetadatum}', 'CustomermetadataController@form')->name('api.customermetadatum.form');
    Route::post('/customermetadata/save', 'CustomermetadataController@post')->name('api.customermetadatum.save');
    Route::post('/customermetadata/{customermetadatum}/delete', 'CustomermetadataController@delete')->name('api.customermetadatum.delete');
    Route::post('/customermetadata/{customermetadatum}/restore', 'CustomermetadataController@restore')->name('api.customermetadatum.restore');
    Route::post('/customermetadata/{customermetadatum}/force-delete', 'CustomermetadataController@forceDelete')->name('api.customermetadatum.force-delete');

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
*/

    // Providermetadatum Route
    // Route::get('providermetadata', 'ProvidermetadataController@index')->name('api.providermetadatum.index');
    // Route::get('/providermetadata/{providermetadatum}', 'ProvidermetadataController@form')->name('api.providermetadatum.form');
    // Route::post('/providermetadata/save', 'ProvidermetadataController@post')->name('api.providermetadatum.save');
    // Route::post('/providermetadata/{providermetadatum}/delete', 'ProvidermetadataController@delete')->name('api.providermetadatum.delete');
    // Route::post('/providermetadata/{providermetadatum}/restore', 'ProvidermetadataController@restore')->name('api.providermetadatum.restore');
    // Route::post('/providermetadata/{providermetadatum}/force-delete', 'ProvidermetadataController@forceDelete')->name('api.providermetadatum.force-delete');

   // provider working hours

    Route::prefix('/provider')->group(function () {
        Route::post('/working-hours/add', 'Working_hoursController@addworking_hours')
            ->name('api.admin.provider.working-hours.create');
        Route::get('/working-hours', 'Working_hoursController@getworking_hours')
            ->name('api.admin.provider.working-hours.index');

        Route::get('/services/{pid}', 'BookingController@GetServiceByProvider')
            ->name('api.admin.provider.services.index');
        Route::get('/services/{userId}/categories/{categoryId}', 'BookingController@getProviderServicesByCategory')
            ->name('api.admin.provider.category-services');;

        Route::post('/services', 'ServiceController@save_provider_servicemap')
            ->name('api.admin.provider.services.save');

        Route::get('/postcodes', 'PostcodesController@getproviderpostcode')
            ->name('api.admin.provider.postcodes.index');
        Route::post('/postcodes/delete', 'PostcodesController@deleteproviderpostcode')
            ->name('api.admin.provider.postcodes.delete');
        Route::post('/postcodes', 'PostcodesController@addproviderpostcode')
            ->name('api.admin.provider.postcodes.create');

        Route::get('/badges', 'UserBadgesController@index')
            ->name('api.admin.provider.badges.index');
        
        Route::post('/badges/{id}/delete', 'UserBadgesController@deleteBadge')
            ->name('api.admin.provider.badges.delete');

        Route::post('/badges', 'UserBadgesController@saveBadge')
            ->name('api.admin.provider.badges.save');


    });

    Route::prefix('/summary')->group(function () {
        Route::get('/new-providers', 'AdminSummaryController@getNewProviders')
            ->name('api.admin.new-providers');

        Route::get('/new-users', 'AdminSummaryController@getNewUsers')
            ->name('api.admin.new-users');

        Route::get('/new-bookings', 'AdminSummaryController@getNewBookings')
            ->name('api.admin.new-bookings');
    });


    // Providerservicemap Route : TODO : Need to discuss
    /*Route::get('providerservicemaps', 'ProviderservicemapsController@index')->name('api.providerservicemap.index');
    Route::get('/providerservicemaps/{providerservicemap}', 'ProviderservicemapsController@form')->name('api.providerservicemap.form');
    Route::post('/providerservicemaps/save', 'ProviderservicemapsController@post')->name('api.providerservicemap.save');
    Route::post('/providerservicemaps/{providerservicemap}/delete', 'ProviderservicemapsController@delete')->name('api.providerservicemap.delete');
    Route::post('/providerservicemaps/{providerservicemap}/restore', 'ProviderservicemapsController@restore')->name('api.providerservicemap.restore');
    Route::post('/providerservicemaps/{providerservicemap}/force-delete', 'ProviderservicemapsController@forceDelete')->name('api.providerservicemap.force-delete');


    
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

   
    // Setting Route
    Route::get('settings', 'SettingsController@index')->name('api.setting.index');
    Route::get('/settings/{setting}', 'SettingsController@form')->name('api.setting.form');
    Route::post('/settings/save', 'SettingsController@post')->name('api.setting.save');
    Route::post('/settings/{setting}/delete', 'SettingsController@delete')->name('api.setting.delete');
    Route::post('/settings/{setting}/restore', 'SettingsController@restore')->name('api.setting.restore');
    Route::post('/settings/{setting}/force-delete', 'SettingsController@forceDelete')->name('api.setting.force-delete');

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
*/
    // Admin Badges crud Routes
    Route::get('/badges', 'BadgesController@index')
        ->name('api.admin.badges.index');
    Route::post('/badges', 'BadgesController@store')
        ->name('api.admin.badges.create');

    Route::get('/badges/{id}', 'BadgesController@show')
        ->name('api.admin.badges.edit');

    Route::put('/badges/{id}', 'BadgesController@update')
        ->name('api.admin.badges.update');

    Route::post('/badges/{id}/delete', 'BadgesController@delete')
        ->name('api.admin.badges.delete');

    Route::post('/badges/{badge}/restore', 'BadgesController@restore')->name('api.badge.restore');
    Route::post('/badges/{badge}/force-delete', 'BadgesController@forceDelete')->name('api.badge.force-delete');
    Route::post('/upload-badge-picture', 'BadgesController@uploadBadgePicture')->name('api.badge.upload');

    Route::get('/discounts', 'DiscountsController@index')
        ->name('api.admin.discounts.index');

    Route::get('/discounts/types', 'DiscountsController@getDiscountTypes')
        ->name('api.admin.discounts.types');

    Route::post('/discounts', 'DiscountsController@store')
        ->name('api.admin.discounts.create');

    Route::get('/discounts/{id}', 'DiscountsController@show')
        ->name('api.admin.discounts.edit');

    Route::put('/discounts/{id}', 'DiscountsController@update')
        ->name('api.admin.discounts.update');

    Route::delete('/discounts/{id}', 'DiscountsController@delete')
        ->name('api.admin.discounts.delete');

    // Userbadge Route
    /*Route::get('userbadges', 'UserbadgesController@index')->name('api.userbadge.index');
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
*/

    // Bookingactivitylog Route
    Route::get('booking-activity-logs', 'BookingActivityLogsController@index')->name('api.booking.activity-logs');
   // Route::get('/bookingactivitylogs/{bookingactivitylog}', 'BookingActivityLogsController@form')->name('api.bookingactivitylog.form');
   // Route::post('/bookingactivitylogs/save', 'BookingActivityLogsController@post')->name('api.bookingactivitylog.save');
   // Route::post('/bookingactivitylogs/{bookingactivitylog}/delete', 'BookingActivityLogsController@delete')->name('api.bookingactivitylog.delete');
   // Route::post('/bookingactivitylogs/{bookingactivitylog}/restore', 'BookingActivityLogsController@restore')->name('api.bookingactivitylog.restore');
   // Route::post('/bookingactivitylogs/{bookingactivitylog}/force-delete', 'BookingActivityLogsController@forceDelete')->name('api.bookingactivitylog.force-delete');


    // Userreview Route
   /* Route::get('userreviews', 'UserreviewsController@index')->name('api.userreview.index');
    Route::get('/userreviews/{userreview}', 'UserreviewsController@form')->name('api.userreview.form');
    Route::post('/userreviews/save', 'UserreviewsController@post')->name('api.userreview.save');
    Route::post('/userreviews/{userreview}/delete', 'UserreviewsController@delete')->name('api.userreview.delete');
    Route::post('/userreviews/{userreview}/restore', 'UserreviewsController@restore')->name('api.userreview.restore');
    Route::post('/userreviews/{userreview}/force-delete', 'UserreviewsController@forceDelete')->name('api.userreview.force-delete');


    // Bookingquestion Route
    Route::get('bookingquestions', 'BookingquestionsController@index')->name('api.bookingquestion.index');
    Route::get('/bookingquestions/{bookingquestion}', 'BookingquestionsController@form')->name('api.bookingquestion.form');
    Route::post('/bookingquestions/save', 'BookingquestionsController@post')->name('api.bookingquestion.save');
    Route::post('/bookingquestions/{bookingquestion}/delete', 'BookingquestionsController@delete')->name('api.bookingquestion.delete');
    Route::post('/bookingquestions/{bookingquestion}/restore', 'BookingquestionsController@restore')->name('api.bookingquestion.restore');
    Route::post('/bookingquestions/{bookingquestion}/force-delete', 'BookingquestionsController@forceDelete')->name('api.bookingquestion.force-delete');


    // Bookingaddress Route
    Route::get('bookingaddresses', 'BookingaddressesController@index')->name('api.bookingaddress.index');
    Route::get('/bookingaddresses/{bookingaddress}', 'BookingaddressesController@form')->name('api.bookingaddress.form');
    Route::post('/bookingaddresses/save', 'BookingaddressesController@post')->name('api.bookingaddress.save');
    Route::post('/bookingaddresses/{bookingaddress}/delete', 'BookingaddressesController@delete')->name('api.bookingaddress.delete');
    Route::post('/bookingaddresses/{bookingaddress}/restore', 'BookingaddressesController@restore')->name('api.bookingaddress.restore');
    Route::post('/bookingaddresses/{bookingaddress}/force-delete', 'BookingaddressesController@forceDelete')->name('api.bookingaddress.force-delete');*/

});


//provider api
Route::namespace('Backend\API')->prefix('v1/provider')->middleware(['auth:api,scope:provider'])->group(function () {
   
    Route::post('saveproviderservicemap', 'ServiceController@save_provider_servicemap')->name('saveproviderservicemap');
    
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


    /* Route::post('postcodes', 'PostcodesController@add_postcodes')->name('api.postcodes.save')->middleware(['scope:admin']);
    Route::post('postcodes/{postcodes_uuid}', 'PostcodesController@edit_postcodes')->name('api.postcodes.edit')->middleware(['scope:admin']);
    Route::get('postcodes', 'PostcodesController@get_all_postcodes')->name('api.postcodes.get_all_postcodes')->middleware(['scope:admin']);
     Route::get('postcodes/{postcodes_uuid}', 'PostcodesController@get')->name('api.postcodes.get')->middleware(['scope:admin']);
     Route::delete('postcodes/{postcodes_uuid}', 'PostcodesController@delete_postcodes')->name('api.postcodes.delete')->middleware(['scope:admin']); */
});
