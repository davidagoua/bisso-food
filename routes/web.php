<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CuisineController;
use App\Http\Controllers\Admin\DeliveryZoneController;
use App\Http\Controllers\Admin\PromoCodeController;
use App\Http\Controllers\Admin\DeliveryPersonController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\SubMenuController;
use App\Http\Controllers\Admin\VendorDiscountController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\SubmenuCustomizationTypeController;
use App\Http\Controllers\Admin\VendorBankDetailController;
use App\Http\Controllers\Admin\SubmenuCustomizationItemController;
use App\Http\Controllers\Vendor\VendorSettingController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Vendor\CustimizationTypeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\DeliveryZoneAreaController;
use App\Http\Controllers\Admin\NotificationTemplateController;
use App\Http\Controllers\Admin\RefaundController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\UserApiController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\multiDeleteController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\Customercontroller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    return "Cache is cleared";
});

Auth::routes();

Route::get('/', [AdminController::class, 'showLogin']);
Route::get('/import', [AdminController::class, 'import'])->name('import');

Route::get('transction_verify/{id}',[UserApiController::class,'transction_verify']);

Route::post('confirm_login', [AdminController::class, 'confirm_login']);
Route::get('admin/forgot_password',[AdminController::class, 'forgot_password']);
Route::post('admin/admin_forgot_password',[AdminController::class, 'admin_forgot_password']);

//Admin
Route::middleware(['auth'])->prefix('admin')->group(function ()
{


    // Send Custom Notification
    Route::get('send_notification',[NotificationTemplateController::class,'send_notification']);
    Route::post('send_notification_user',[NotificationTemplateController::class,'send_notification_user']);
    Route::post('send_notification_vendor',[NotificationTemplateController::class,'send_notification_vendor']);
    // Route::resources('notification_template',Admin\NotificationTemplateController::class);

    Route::middleware(['XSS'])->group(function(){


    Route::get('orderChart',[HomeController::class,'orderChart']);
    Route::post('orderChart',[HomeController::class,'orderChart']);

    Route::get('earningChart',[HomeController::class,'earningChart']);
    Route::post('earningChart',[HomeController::class,'earningChart']);
    Route::get('topItems',[HomeController::class,'topItems']);
    Route::get('avarageItems',[HomeController::class,'avarageItems']);

    Route::resources([
        'delivery_person' => Admin\DeliveryPersonController::class,
        'delivery_zone' => Admin\DeliveryZoneController::class,
        'promo_code' => Admin\PromoCodeController::class,
        'vendor' => Admin\VendorController::class,
        'cuisine' => Admin\CuisineController::class,
        'banner' => Admin\BannerController::class,
        'roles' => Admin\RoleController::class,
        'user' => Admin\UserController::class,
        'menu' => Admin\MenuController::class,
        'submenu' => Admin\SubMenuController::class,
        'faq' => Admin\FaqController::class,
        'notification_template' => Admin\NotificationTemplateController::class,
        'order' => Admin\OrderController::class,
        'language' => Admin\LanguageController::class,
        'refund' => Admin\RefaundController::class,
        'tax' => Admin\TaxController::class,
    ]);

    Route::get('user_bank_details/{id}',[RefaundController::class,'user_bank_details']);
    Route::post('refund/refund_status',[RefaundController::class,'status']);
    Route::post('refund/refaundStripePayment',[RefaundController::class,'refaundStripePayment']);
    Route::post('refund/confirm_refund',[RefaundController::class,'confirm_refund']);

    Route::get('delivery_person_finance_details/{id}',[DeliveryPersonController::class,'finance_details']);
    Route::post('driver_make_payment',[DeliveryPersonController::class,'driver_make_payment']);
    Route::post('driver_settle',[DeliveryPersonController::class,'driver_settle']);
    Route::get('show_driver_settle_details/{duration}/{driver_id}',[DeliveryPersonController::class,'show_driver_settle_details']);
    Route::post('/driver_fluterPayment',[DeliveryPersonController::class,'driver_fluterPayment']);
    Route::get('/driver_transction/{duration}/{driver_id}',[DeliveryPersonController::class,'driver_transction']);

    //Delivery zone area
    Route::get('delivery_zone_area/{id}', [DeliveryZoneAreaController::class,'index']);
    Route::resource('delivery_zone_area', Admin\DeliveryZoneAreaController::class)->except([
        'index','show'
    ]);

    Route::get('delivery_zone_area/delivery_zone_area_map/{id}',[DeliveryZoneAreaController::class,'delivery_zone_area_map']);
    Route::get('order/invoice/{id}', [OrderController::class,'invoice']);
    Route::get('order/invoice_print/{id}', [OrderController::class,'invoice_print']);
    Route::get('/vendor_discount/{id}', [VendorDiscountController::class,'index']);
    Route::get('/vendor_discount/create/{id}', [VendorDiscountController::class,'create']);
    Route::resource('vendor_discount', Admin\VendorDiscountController::class)->except([
        'index','create'
    ]);
    Route::get('/vendor_discounts', [VendorDiscountController::class,'index']);

    // Customization type
    Route::get('/customization_type/{id}', [SubmenuCustomizationTypeController::class,'index']);
    Route::get('/customization_type/create/{id}', [SubmenuCustomizationTypeController::class,'create']);
    Route::resource('customization_type', Admin\SubmenuCustomizationTypeController::class)->except([
        'index','create'
    ]);
    Route::post('customization_type/updateItem',[SubmenuCustomizationTypeController::class,'updateItem']);

    //Customization item
    Route::get('/customization_item/{id}', [SubmenuCustomizationItemController::class,'index']);
    Route::get('/customization_item/create/{id}', [SubmenuCustomizationItemController::class,'create']);
    Route::resource('customization_item', Admin\SubmenuCustomizationItemController::class)->except([
        'index','create'
    ]);

    Route::get('/edit_delivery_time/{id}' , [VendorController::class,'edit_delivery_time']);
    Route::get('/edit_pick_up_time/{id}',[VendorController::class,'edit_pick_up_time']);
    Route::get('/edit_selling_timeslot/{id}',[VendorController::class,'edit_selling_timeslot']);
    Route::post('/update_delivery_time',[VendorController::class,'update_delivery_time']);
    Route::post('/update_pick_up_time',[VendorController::class,'update_pick_up_time']);
    Route::post('/update_selling_timeslot',[VendorController::class,'update_selling_timeslot']);
    Route::get('/finance_details/{id}',[VendorController::class,'finance_details']);
    Route::get('/rattings/{id}',[VendorController::class,'rattings']);
    Route::post('/make_payment',[VendorController::class,'make_payment']);
    Route::post('/stripePayment',[VendorController::class,'stripePayment']);
    Route::post('/fluterPayment',[VendorController::class,'fluterPayment']);
    Route::get('/transction/{duration}/{vendor_id}',[VendorController::class,'transction']);
    Route::get('/show_settalement/{duration}',[VendorController::class,'show_settalement']);

    Route::get('/home', [HomeController::class, 'index']);
    Route::get('admin_profile',[AdminController::class, 'admin_profile']);

    Route::post('update_admin_profile',[AdminController::class, 'update_admin_profile']);
    Route::post('update_password',[AdminController::class, 'change_password']);
    Route::get('feedback',[AdminController::class,'feedback']);

    //Setting
    Route::get('setting',[SettingController::class, 'setting']);
    Route::get('general_setting',[SettingController::class, 'general_setting']);
    Route::get('order_setting',[SettingController::class,'order_setting']);
    Route::get('delivery_person_setting',[SettingController::class,'delivery_person_setting']);

    Route::get('verification_setting',[SettingController::class,'verification_setting']);
    Route::post('update_verification_seting',[SettingController::class,'update_verification_seting']);
    Route::post('update_status',[SettingController::class,'update_status']);

    Route::post('update_general_setting',[SettingController::class, 'update_general_setting']);
    Route::post('update_order_setting',[SettingController::class, 'update_order_setting']);
    Route::post('update_delivery_person_setting',[SettingController::class, 'update_delivery_person_setting']);

    Route::post('update_privacy',[SettingController::class,'update_privacy']);
    Route::post('update_terms',[SettingController::class,'update_terms']);
    Route::post('update_help',[SettingController::class,'update_help']);
    Route::post('update_about',[SettingController::class,'update_about']);
    Route::post('update_company_details',[SettingController::class,'update_company_details']);

    Route::get('notification_setting',[SettingController::class, 'notification_setting']);
    Route::post('update_customer_notification',[SettingController::class, 'update_customer_notification']);
    Route::post('update_driver_notification',[SettingController::class, 'update_driver_notification']);
    Route::post('update_vendor_notification',[SettingController::class, 'update_vendor_notification']);
    Route::post('update_mail_setting',[SettingController::class, 'update_mail_setting']);
    Route::post('/test_mail',[SettingController::class,'testMail']);

    Route::post('update_noti',[SettingController::class, 'update_noti']);

    Route::get('version_setting',[SettingController::class,'version_setting']);
    Route::get('static_pages',[SettingController::class,'static_pages']);

    Route::get('payment_setting',[SettingController::class, 'payment_setting']);
    Route::post('update_stripe_setting',[SettingController::class,'update_stripe_setting']);
    Route::post('update_version_setting',[SettingController::class,'update_version_setting']);

    Route::post('update_paypal',[SettingController::class,'update_paypal']);
    Route::post('update_razorpay',[SettingController::class,'update_razorpay']);
    Route::post('update_flutterwave',[SettingController::class,'update_flutterwave']);

    Route::get('vendor_change_password/{id}',[VendorController::class,'vendor_change_password']);
    Route::post('vendor_update_password/{id}',[VendorController::class,'vendor_update_password']);

    Route::get('vendor_bank_details/{id}',[VendorBankDetailController::class,'vendor_bank_details']);
    Route::post('add_bank_details',[VendorBankDetailController::class,'add_bank_details']);
    Route::post('update_bank_details/{id}',[VendorBankDetailController::class,'update_bank_details']);
    Route::get('license_setting',[SettingController::class,'license_setting']);
    Route::post('update_license',[SettingController::class,'update_license']);

    //change status
    Route::post('delivery_zone/change_status',[DeliveryZoneController::class, 'change_status']);
    Route::post('promo_code/change_status',[PromoCodeController::class, 'change_status']);
    Route::post('delivery_person/change_status',[DeliveryPersonController::class, 'change_status']);
    Route::post('cuisine/change_status',[CuisineController::class, 'change_status']);
    Route::post('user/change_status',[UserController::class, 'change_status']);
    Route::post('vendor/change_status',[VendorController::class, 'change_status']);
    Route::post('menu/change_status',[MenuController::class, 'change_status']);
    Route::post('submenu/change_status',[SubMenuController::class, 'change_status']);
    Route::post('menu_category/change_status',[MenuCategoryController::class, 'change_status']);
    Route::post('banner/change_status',[BannerController::class, 'change_status']);
    Route::post('language/change_status',[LanguageController::class, 'change_status']);
    Route::post('submenu/selling_timeslot',[SubMenuController::class,'selling_timeslot']);
    Route::post('settle',[App\Http\Controllers\Admin\VendorController::class,'settle']);
    Route::post('tax/change_status', [TaxController::class,'change_status']);

    //Change password
    Route::post('change_password',[AdminController::class, 'change_password']);
    Route::get('user_report',[ReportController::class,'user_report']);
    Route::post('user_report',[ReportController::class,'user_report']);
    Route::get('order_report',[ReportController::class,'order_report']);
    Route::post('order_report',[ReportController::class,'order_report']);
    Route::get('vendor_report',[ReportController::class,'vendor_report']);
    Route::post('vendor_report',[ReportController::class,'vendor_report']);
    Route::get('wallet_withdraw_report',[ReportController::class,'wallet_withdraw_report']);
    Route::post('wallet_withdraw_report',[ReportController::class,'wallet_withdraw_report']);
    Route::get('wallet_deposit_report',[ReportController::class,'wallet_deposit_report']);
    Route::post('wallet_deposit_report',[ReportController::class,'wallet_deposit_report']);
    Route::get('driver_report',[ReportController::class,'driver_report']);
    Route::post('driver_report',[ReportController::class,'driver_report']);
    Route::get('earning_report',[ReportController::class,'earning_report']);
    Route::get('change_language/{name}',[AdminController::class,'change_language']);

    // Multi Delete
    Route::post('/cuisine_multi_delete',[multiDeleteController::class,'cuisine_delete']);
    Route::post('/vendor_multi_delete',[multiDeleteController::class,'vendor_delete']);
    Route::post('/submenu_multi_delete',[multiDeleteController::class,'submenu_delete']);
    Route::post('/menu_multi_delete',[multiDeleteController::class,'menu_delete']);
    Route::post('/order_multi_delete',[multiDeleteController::class,'order_delete']);
    Route::post('/delivery_person_multi_delete',[multiDeleteController::class,'delivery_person_delete']);
    Route::post('/delivery_zone_multi_delete',[multiDeleteController::class,'delivery_zone_delete']);
    Route::post('/promo_code_multi_delete',[multiDeleteController::class,'promo_code_delete']);
    Route::post('/user_multi_delete',[multiDeleteController::class,'user_multi_delete']);
    Route::post('/faq_multi_delete',[multiDeleteController::class,'faq_multi_delete']);
    Route::post('/banner_multi_delete',[multiDeleteController::class,'banner_multi_delete']);
    Route::post('/tax_multi_delete',[multiDeleteController::class,'tax_multi_delete']);
    Route::post('/vendor_discount_multi_delete',[multiDeleteController::class,'vendor_discount_multi_delete']);

    // Bulk Import
    Route::post('/submenu_import/{id}',[SubMenuController::class,'submenu_import']);

    // download PDF
    Route::get('download_pdf/{excel_file}',[AdminController::class,'download_pdf']);

    // user wallet
    Route::get('user/user_wallet/{user_id}',[UserController::class,'user_wallet']);
    Route::post('user/user_wallet/{user_id}',[UserController::class,'user_wallet']);
    Route::post('user/add_wallet',[UserController::class,'add_wallet']);

    Route::post('/check-sms',[SettingController::class,'check_sms']);
    Route::get('/login-to-vendor/{id}', [VendorController::class,'vendor_login']);
});
});

//Vendor
// Route::middleware(['XSS'])->group(function(){

Route::get('/vendor/login',[VendorSettingController::class,'login']);
Route::post('/vendor/vendor_confirm_login',[VendorSettingController::class,'vendor_confirm_login']);
Route::get('vendor/register_vendor',[VendorSettingController::class,'register_vendor']);
Route::post('vendor/register',[VendorSettingController::class,'register']);
Route::get('vendor/vendor_home',[VendorSettingController::class,'vendor_home']);
Route::get('vendor/notification',[VendorSettingController::class, 'notification']);
Route::get('vendor/forgot_password',[VendorSettingController::class, 'forgot_password']);
Route::post('admin/admin_forgot_password',[AdminController::class, 'admin_forgot_password']);
Route::get('vendor/send_otp/{id}',[VendorSettingController::class,'send_otp']);
Route::post('vendor/check_otp',[VendorSettingController::class,'check_otp']);
// });

Route::middleware(['auth'])->prefix('vendor')->group(function ()
{
    Route::middleware(['XSS'])->group(function(){


    Route::get('user_report',[App\Http\Controllers\Vendor\ReportController::class,'user_report']);
    Route::post('user_report',[App\Http\Controllers\Vendor\ReportController::class,'user_report']);

    Route::get('order_report',[App\Http\Controllers\Vendor\ReportController::class,'order_report']);
    Route::post('order_report',[App\Http\Controllers\Vendor\ReportController::class,'order_report']);

    Route::get('cancel_max_order',[VendorSettingController::class,'cancel_max_order']);
    Route::get('orderChart',[VendorSettingController::class,'orderChart']);
    Route::get('revenueChart',[VendorSettingController::class,'revenueChart']);
    Route::get('vendorAvarageTime',[VendorSettingController::class,'vendorAvarageTime']);
    Route::get('change_password',[VendorSettingController::class,'change_password']);
    Route::post('update_pwd',[VendorSettingController::class,'update_pwd']);

    Route::get('vendor/vendor_finance_details',[App\Http\Controllers\Vendor\VendorDiscountController::class,'vendor_finance_details']);
    Route::get('vendor/delivery_timeslot',[App\Http\Controllers\Vendor\VendorController::class,'delivery_timeslot']);
    Route::get('vendor/pickup_timeslot',[App\Http\Controllers\Vendor\VendorController::class,'pickup_timeslot']);

    Route::get('vendor/selling_timeslot',[App\Http\Controllers\Vendor\VendorController::class,'selling_timeslot']);
    Route::get('order/transaction/{duration}',[App\Http\Controllers\Vendor\VendorController::class,'transaction']);

    // add_user
    // Route::post('add_user',[App\Http\Controllers\Vendor\VendorController::class,'add_user']);

    Route::get('rattings',[App\Http\Controllers\Vendor\VendorController::class,'rattings']);

    Route::get('bank_details',[App\Http\Controllers\Vendor\VendorController::class,'bank_details']);
    Route::post('add_vendor_bank_details',[App\Http\Controllers\Vendor\VendorController::class,'add_vendor_bank_details']);
    Route::post('edit_vendor_bank_details/{id}',[App\Http\Controllers\Vendor\VendorController::class,'edit_vendor_bank_details']);

    Route::post('cart',[App\Http\Controllers\Vendor\OrderController::class,'cart']);
    Route::get('DispCustimization/{submenu_id}',[App\Http\Controllers\Vendor\OrderController::class,'custimization']);
    Route::post('update_custimization',[App\Http\Controllers\Vendor\OrderController::class,'update_custimization']);
    Route::post('add_user',[App\Http\Controllers\Vendor\OrderController::class,'add_user']);
    Route::get('display_bill',[App\Http\Controllers\Vendor\OrderController::class,'display_bill']);
    Route::post('displayBillWithCoupen',[App\Http\Controllers\Vendor\OrderController::class,'displayBillWithCoupen']);
    Route::post('change_submenu',[App\Http\Controllers\Vendor\OrderController::class,'change_submenu']);
    Route::post('order/change_status',[App\Http\Controllers\Vendor\OrderController::class,'change_status']);
    Route::get('month_finanace',[App\Http\Controllers\Vendor\VendorController::class,'month_finanace']);
    Route::post('month',[App\Http\Controllers\Vendor\VendorController::class,'month']);

    Route::get('/deliveryPerson/pending_amount/{order_id}',[DeliveryPersonController::class,'pending_amount']);

    Route::resources([
        'vendor_discount' => Vendor\VendorDiscountController::class,
        'vendor' => Vendor\VendorController::class,
        'menu_category' => Vendor\MenuCategoryController::class,
        'vendor_menu' => Vendor\MenuController::class,
        'vendor_submenu' => Vendor\SubMenuController::class,
        'deliveryPerson' => Admin\DeliveryPersonController::class,
        'deliveryZone' => Admin\DeliveryZoneController::class,
    ]);
    Route::get('/custimization_type/{id}', [CustimizationTypeController::class,'index']);
    Route::get('/custimization_type/create/{id}', [CustimizationTypeController::class,'create']);

    // order
    Route::get('Orders', [App\Http\Controllers\Vendor\OrderController::class,'index']);
    Route::post('Orders', [App\Http\Controllers\Vendor\OrderController::class,'index']);
    Route::resource('order', Vendor\OrderController::class)->except([
        'index'
    ]);

    Route::post('vendor_menu/{menu_id}',[App\Http\Controllers\Vendor\MenuController::class,'show']);

    Route::post('order/driver_assign',[App\Http\Controllers\Vendor\OrderController::class,'driver_assign']);

    // delivery zone area
    Route::get('deliveryZoneArea/{id}', [DeliveryZoneAreaController::class,'index']);
    Route::resource('deliveryZoneArea', Admin\DeliveryZoneAreaController::class)->except([
        'index','show'
    ]);

    Route::get('update_vendor',[VendorSettingController::class,'update_vendor']);

    Route::get('print_thermal/{order_id}',[App\Http\Controllers\Vendor\OrderController::class,'print_thermal']);
    Route::get('printer_setting',[VendorSettingController::class,'print_setting']);
    Route::post('update_printer_setting',[VendorSettingController::class,'update_printer_setting']);
});
});

Route::post('saveEnvData',[AdminController::class,'saveEnvData']);
Route::post('saveAdminData',[AdminController::class,'saveAdminData']);
Route::any('installer',[AdminController::class,'license']);
