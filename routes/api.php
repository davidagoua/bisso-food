<?php

use App\Http\Controllers\DriverApiController;
use App\Http\Controllers\VendorApiController;
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

Route::middleware('auth:api')->get('/user', function (Request $request)
{
    return $request->user();
});

/******  Vendor ********/
Route::post('vendor/login','VendorApiController@apiLogin');
Route::post('vendor/register','VendorApiController@apiRegister');
Route::post('vendor/check_otp','VendorApiController@apiCheckOtp');
Route::post('vendor/resend_otp','VendorApiController@apiResendOtp');
Route::post('vendor/forgot_password','VendorApiController@apiForgotPassword');
Route::post('user_login','UserApiController@apiUserLogin');
Route::post('user_register','UserApiController@apiUserRegister');
Route::post('check_otp','UserApiController@apiCheckOtp');
Route::get('vendor/vendor_setting','VendorApiController@apiVendorSetting');

Route::middleware('auth:api')->prefix('vendor')->group(function ()
{
    /* ---- Vendor ---- */
    //Menu
    Route::get('menu','VendorApiController@apiMenu');
    Route::post('create_menu','VendorApiController@apiCreateMenu');
    Route::get('edit_menu/{menu_id}','VendorApiController@apiEditMenu');
    Route::post('update_menu/{menu_id}','VendorApiController@apiUpdateMenu');
    Route::get('single_menu/{menu_id}','VendorApiController@apiSingleMenu');
    Route::get('delete_menu/{menu_id}','VendorApiController@apiDeleteMenu');

    //Submenu
    Route::get('submenu/{menu_id}','VendorApiController@apiSubmenu');
    Route::get('show_submenu','VendorApiController@apiSubmenu1');
    Route::post('create_submenu','VendorApiController@apiCreateSubmenu');
    Route::get('edit_submenu/{submenu_id}','VendorApiController@apiEditSubmenu');
    Route::post('update_submenu/{submenu_id}','VendorApiController@apiUpdateSubmenu');
    Route::get('single_submenu/{submenu_id}','VendorApiController@apiSingleSubmenu');
    Route::get('delete_submenu/{submenu_id}','VendorApiController@apiDeleteSubmenu');

    //Custimization
    Route::get('custimization/{submenu_id}','VendorApiController@apiCustimization');
    Route::post('create_custimization','VendorApiController@apiCreateCustimization');
    Route::get('edit_custimization/{custimization_id}','VendorApiController@apiEditCustimization');
    Route::post('update_custimization/{custimization_id}','VendorApiController@apiUpdateCustimization');
    Route::post('update_cust_item/{custimization_id}','VendorApiController@apiCustItem');
    Route::get('delete_custimization/{custimization_id}','VendorApiController@apiDeleteCustimization');

    //Delivery timeslot
    Route::get('edit_deliveryTimeslot','VendorApiController@apiEditDeliveryTimeslot');
    Route::post('update_deliveryTimeslot','VendorApiController@apiUpdateDeliveryTimeslot');

    //Pickup timeslot
    Route::get('edit_PickUpTimeslot','VendorApiController@apiEditPickUpTimeslot');
    Route::post('update_PickUpTimeslot','VendorApiController@apiUpdatePickUpTimeslot');

    //Selling timeslot
    Route::get('edit_SellingTimeslot','VendorApiController@apiEditSellingTimeslot');
    Route::post('update_SellingTimeslot','VendorApiController@apiUpdateSellingTimeslot');

    //Discount
    Route::get('discount','VendorApiController@apiDiscount');
    Route::post('create_discount','VendorApiController@apiCreateDiscount');
    Route::get('edit_discount/{discount_id}','VendorApiController@apiEditDiscount');
    Route::post('update_discount/{discount_id}','VendorApiController@apiUpdateDiscount');

    //Bank Details
    Route::get('show_bank_detail','VendorApiController@apiShowBankDetails');
    Route::post('add_bank_detail','VendorApiController@apiAddBankDetails');
    Route::get('edit_bank_detail','VendorApiController@apiEditBankDetails');
    Route::post('update_bank_detail','VendorApiController@apiUpdateBankDetails');

    //Finance Details
    Route::get('last_7_days','VendorApiController@apiLast7Days');
    Route::get('current_month','VendorApiController@apiCurrentMonth');
    Route::post('specific_month','VendorApiController@apiMonth');
    Route::get('finance_details','VendorApiController@apiFinanceDetails');
    Route::get('cash_balance','VendorApiController@apiCashBalance');
    Route::get('insights','VendorApiController@apiInsights');

    //Order
    Route::get('order','VendorApiController@apiOrder');
    Route::post('create_order','VendorApiController@apiCreateOrder');
    Route::get('today_order','VendorApiController@apiTodayOrder');
    Route::get('show_all_order','VendorApiController@apiShowAllOrder');

    // change status
    Route::post('change_status','VendorApiController@apiChangeStatus');

    //user
    Route::get('user','VendorApiController@apiUser');
    Route::post('create_user','VendorApiController@apiCreateUser');

    //User Address
    Route::get('user_address/{user_id}','VendorApiController@apiUserAddress');
    Route::post('create_user_address','VendorApiController@apiCreateUserAddress');

    /* ---- User Password ---- */
    Route::post('change_password','VendorApiController@apiChangePassword');

    // Faq
    Route::get('faq','VendorApiController@apiFaq');

    Route::get('vendor_login','VendorApiController@apiVendorLogin');
    Route::post('update_profile','VendorApiController@apiUpdateProfile');

    // Delivery Zone
    Route::get('delivery_zones','VendorApiController@apiDeliveryZones');
    Route::post('create_deliveryzone','VendorApiController@apiCreateDeliveryZones');
    Route::get('edit_deliveryzone/{zone_id}','VendorApiController@apiEditDeliveryZones');
    Route::post('update_deliveryzone/{delivery_zone_id}','VendorApiController@apiUpdateDeliveryZones');
    Route::get('delete_delivery_zone/{delivery_zone_id}','VendorApiController@apiDeleteDeliveryZones');

    // Delivery Zone Area
    Route::get('delivery_zone_area/{zone_id}','VendorApiController@apiDeliveryZoneArea');
    Route::post('create_delivery_zone_area','VendorApiController@apiCreateDeliveryZoneArea');
    Route::get('edit_delivery_zone_area/{delivery_zone_area_id}','VendorApiController@apiEditDeliveryZoneArea');
    Route::post('update_delivery_zone_area/{delivery_zone_area_id}','VendorApiController@apiUpdateDeliveryZoneArea');
    Route::get('delete_delivery_zone_area/{delivery_zone_area_id}','VendorApiController@apiDeleteDeliveryZoneArea');

    // Delivery Person
    Route::get('delivery_person','VendorApiController@apiDeliveryPerson');
    Route::post('create_delivery_person','VendorApiController@apiCreateDeliveryPerson');
    Route::get('edit_delivery_person/{delivery_person_id}','VendorApiController@apiEditDeliveryPerson');
    Route::post('update_delivery_person/{delivery_person_id}','VendorApiController@apiUpdateDeliveryPerson');
    Route::get('delete_delivery_person/{delivery_person_id}','VendorApiController@apiDeleteDeliveryPerson');
    Route::get('available_driver','VendorApiController@apiAvailableDriver');
    // Assing Driver
    Route::post('assign_driver','VendorApiController@apiAssignDriver');
});

/******  User ********/
Route::middleware('auth:api')->group(function ()
{
    Route::post('book_order','UserApiController@apiBookOrder');
    Route::get('show_order','UserApiController@apiShowOrder');
    Route::post('update_user','UserApiController@apiUpdateUser');
    Route::post('update_image','UserApiController@apiUpdateImage');
    Route::post('faviroute','UserApiController@apiFaviroute');
    Route::post('rest_faviroute','UserApiController@apiRestFaviroute');
    Route::get('user_address','UserApiController@apiUserAddress');
    Route::post('add_address','UserApiController@apiAddAddress');
    Route::get('edit_address/{address_id}','UserApiController@apiEditAddress');
    Route::post('update_address/{address_id}','UserApiController@apiUpdateAddress');
    Route::get('remove_address/{address_id}','UserApiController@apiRemoveAddress');
    Route::post('cancel_order','UserApiController@apiCancelOrder');
    Route::get('single_order/{order_id}','UserApiController@apiSingleOrder');
    Route::post('apply_promo_code','UserApiController@apiApplyPromoCode');
    Route::post('add_review','UserApiController@apiAddReview');
    // Route::post('add_feedback','UserApiController@apiAddFeedback');
    Route::get('user_order_status','UserApiController@apiUserOrderStatus');
    Route::post('refund','UserApiController@apirefund');
    Route::post('bank_details','UserApiController@apiBankDetails');
    Route::get('tracking/{order_id}','UserApiController@apiTracking');
    Route::get('user_balance','UserApiController@apiUserBalance');
    Route::get('wallet_balance','UserApiController@apiWalletBalance');
    Route::post('add_balance','UserApiController@apiUserAddBalance');
    Route::post('user_change_password','UserApiController@apiChangePassword');
    Route::get('payment_setting','UserApiController@apiPaymentSetting');
    Route::get('notification','UserApiController@apiNotification');
});

Route::post('add_feedback','UserApiController@apiAddFeedback');

Route::get('tax','UserApiController@apiTax');
Route::post('user_forgot_password','UserApiController@apiForgotPassword');
Route::post('send_otp','UserApiController@apiSendOtp');
Route::post('filter','UserApiController@apiFilter');
Route::post('cuisine_vendor/{id}','UserApiController@apiCuisineVendor');
Route::post('search','UserApiController@apiSearch');

Route::post('near_by','UserApiController@apiNearBy');
Route::get('menu_category/{vendor_id}','UserApiController@apiMenuCategory');
Route::get('cuisine','UserApiController@apiCuisine');
Route::post('vendor','UserApiController@apiVendor');
Route::get('single_vendor/{vendor_id}','UserApiController@apiSingleVendor');
Route::get('menu/{vendor_id}','UserApiController@apiMenu');
Route::get('promo_code/{vendor_id}','UserApiController@apiPromoCode');
Route::get('faq','UserApiController@apiFaq');
Route::get('banner','UserApiController@apiBanner');
Route::get('single_menu/{menu_id}','UserApiController@apiSingleMenu');
Route::get('setting','UserApiController@apiSetting');
Route::get('order_setting','UserApiController@apiOrderSetting');
Route::post('veg_rest','UserApiController@apiVegRest');
Route::post('nonveg_rest','UserApiController@apiNonVegRest');
Route::post('top_rest','UserApiController@apiTopRest');
Route::post('explore_rest','UserApiController@apiExploreRest');

/******  Driver ********/
Route::post('driver/driver_login','DriverApiController@apiDriverLogin');
Route::post('driver/driver_check_otp','DriverApiController@apiDriverCheckOtp');
Route::post('driver/driver_register','DriverApiController@apiDriverRegister');
Route::post('driver/driver_change_password','DriverApiController@apiDriverChangePassword');
Route::post('driver/driver_resendOtp','DriverApiController@apiReSendOtp');
Route::get('driver/driver_faq','DriverApiController@apiDriverFaq');
Route::get('driver/driver_setting','DriverApiController@apiDriverSetting');

Route::post('driver/forgot_password_otp','DriverApiController@apiForgotPasswordOtp');
Route::post('driver/forgot_password_check_otp','DriverApiController@apiForgotPasswordCheckOtp');
Route::post('driver/forgot_password','DriverApiController@apiForgotPassword');

Route::middleware('auth:driverApi')->prefix('driver')->group(function ()
{
    Route::post('set_location','DriverApiController@apiSetLocation');
    Route::get('driver_order','DriverApiController@apiDriverOrder');
    Route::post('status_change','DriverApiController@apiStatusChange');
    Route::get('driver','DriverApiController@apiDriver');

    Route::post('update_driver','DriverApiController@apiUpdateDriver');
    Route::post('update_driver_image','DriverApiController@apiDriverImage');
    Route::get('order_history','DriverApiController@apiOrderHistory');
    Route::get('order_earning','DriverApiController@apiOrderEarning');
    Route::get('earning','DriverApiController@apiEarningHistory');

    Route::post('update_document','DriverApiController@apiUpdateVehical');
    Route::get('notification','DriverApiController@apiDriverNotification');
    Route::post('update_lat_lang','DriverApiController@apiUpdateLatLang');
    Route::post('delivery_person_change_password','DriverApiController@apiDeliveryPersonChangePassword');

    Route::get('delivery_zone','DriverApiController@apiDeliveryZone');
    Route::get('current_order','DriverApiController@apiSingleOrder');
});
