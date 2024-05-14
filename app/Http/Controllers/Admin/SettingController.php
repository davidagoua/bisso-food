<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Mail\TestMail;
use App\Models\Country;
use App\Models\Currency;
use App\Models\GeneralSetting;
use App\Models\OrderSetting;
use App\Models\PaymentSetting;
use App\Models\Timezone;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use LicenseBoxExternalAPI;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;

class SettingController extends Controller
{
    public function setting()
    {
        $admin = User::whereHas('roles', function($q)
        {
            $q->where('title','admin');
        })->first();
        $countries = Country::get();
        $currencies = Currency::get();
        return view('admin.setting.setting',compact('admin','countries','currencies'));
    }

    public function general_setting()
    {
        $general_setting = GeneralSetting::first();
        $general_setting['start_time'] = Carbon::parse($general_setting['start_time'])->format('H:i');
        $general_setting['end_time'] = Carbon::parse($general_setting['end_time'])->format('H:i');
        $countries = Country::get();
        $currencies = Currency::get();
        $timezones = Timezone::get();
        return view('admin.setting.general_setting',compact('countries','timezones','currencies','general_setting'));
    }

    public function update_general_setting(Request $request)
    {
        if(!isset($request->business_availability)){
            $request->validate(
            [
                'message' => 'required',
            ],
            [
                'message.required' => 'Message Field Is Required When Bussiness Availibity is Off',
            ]
        );
        }
        if(!isset($request->item_tax)){
            $request->validate(
            [
                'item_tax' => 'required',
            ],
            [
                'item_tax.required' => 'Item Tax Field Is Required When All Items Price Included In Tax Is Off',
            ]
        );
        }
        $data = $request->all();
        $data['start_time'] = Carbon::parse($data['start_time'])->format('h:i a');
        $data['end_time'] = Carbon::parse($data['end_time'])->format('h:i a');
        $id = GeneralSetting::first();
        $data['business_availability'] = $request->has('business_availability') ? 1 : 0;
        $data['isItemTax'] = $request->has('isItemTax') ? 1 : 0;
        $data['isPickup'] = $request->has('isPickup') ? 1 : 0;
        $data['isSameDayDelivery'] = $request->has('isSameDayDelivery') ? 1 : 0;
        if ($file = $request->hasfile('company_black_logo'))
        {
            $request->validate(
            ['company_black_logo' => 'max:1000'],
            [
                'company_black_logo.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            (new CustomController)->deleteImage(DB::table('general_setting')->where('id', $id->id)->value('company_black_logo'));
            $data['company_black_logo'] = (new CustomController)->uploadImage($request->company_black_logo);
        }
        if ($file = $request->hasfile('favicon'))
        {
            $request->validate(
            ['favicon' => 'max:1000'],
            [
                'favicon.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            (new CustomController)->deleteImage(DB::table('general_setting')->where('id', $id->id)->value('favicon'));
            $data['favicon'] = (new CustomController)->uploadImage($request->favicon);
        }
        $timezone['timezone']=$data['timezone'];
        $this->updateENV($timezone);
        $symbol = Currency::where('code',$data['currency'])->first();
        $data['currency_symbol'] = $symbol->symbol;
        $id->update($data);
        return redirect('admin/general_setting')->with('msg','setting changed successfully..!!');
    }

    public function update_flutterwave(Request $request)
    {
        $id = PaymentSetting::first();
        $data = $request->all();
        $data['flutterwave'] = 1;
        $data['is_debbug_mode'] = $request->is_debbug_mode;
        $id->update($data);
        return redirect()->back()->with('msg','setting changed successfully..!!');
    }

    public function version_setting()
    {
        $general_setting = GeneralSetting::first();
        return view('admin.setting.version_setting',compact('general_setting'));
    }

    public function notification_setting()
    {
        $notification_setting = GeneralSetting::first();
        return view('admin.setting.notification_setting',compact('notification_setting'));
    }

    public function update_customer_notification(Request $request)
    {
        $data = $request->all();
        $id = GeneralSetting::first();
        $data['customer_notification'] = 1;
        $onesignal['customer_app_id']=$request->customer_app_id;
        $onesignal['customer_auth_key'] = $request->customer_auth_key;
        $onesignal['customer_api_key'] = $request->customer_api_key;
        $this->updateENV($onesignal);
        $id->update($data);
        return redirect('admin/notification_setting')->with('msg','setting changed successfully..!!');
    }

    public function update_driver_notification(Request $request)
    {
        $data = $request->all();
        $id = GeneralSetting::first();
        $data['driver_notification'] = 1;
        $onesignal['driver_app_id']=$request->driver_app_id;
        $onesignal['driver_auth_key'] = $request->driver_auth_key;
        $onesignal['driver_api_key'] = $request->driver_api_key;
        $this->updateENV($onesignal);
        $id->update($data);
        return redirect('admin/notification_setting')->with('msg','setting changed successfully..!!');
    }

    public function update_vendor_notification(Request $request)
    {
        $data = $request->all();
        $id = GeneralSetting::first();
        $data['vendor_notification'] = 1;
        $onesignal['vendor_app_id']=$request->vendor_app_id;
        $onesignal['vendor_auth_key'] = $request->vendor_auth_key;
        $onesignal['vendor_api_key'] = $request->vendor_api_key;
        $this->updateENV($onesignal);
        $id->update($data);
        return redirect('admin/notification_setting')->with('msg','setting changed successfully..!!');
    }

    public function update_mail_setting(Request $request)
    {
        $data = $request->all();
        $data['mail_mailer'] = 'smtp';
        $id = GeneralSetting::first();
        $data['customer_mail'] = $request->has('customer_mail') ? 1 : 0;
        $data['vendor_mail'] = $request->has('vendor_mail') ? 1 : 0;
        $data['driver_mail'] = $request->has('driver_mail') ? 1 : 0;
        $mail_setting = [
            'MAIL_MAILER' => 'smtp',
            'MAIL_HOST' => $request->mail_host,
            'MAIL_PORT' => $request->mail_port,
            'MAIL_USERNAME' => $request->mail_username,
            'MAIL_PASSWORD' => $request->mail_password,
            'MAIL_ENCRYPTION' => $request->mail_encryption,
            'MAIL_FROM_ADDRESS' => $request->mail_from_address,
            'MAIL_FROM_NAME' => str_replace(' ', '', $id->business_name),
        ];
        $id->update($data);
        $this->updateENV($mail_setting);
        return redirect('admin/notification_setting')->with('msg','setting changed successfully..!!');
    }

    public function payment_setting()
    {
        $PaymentSetting = PaymentSetting::first();
        return view('admin.setting.payment_setting',compact('PaymentSetting'));
    }

    public function update_stripe_setting(Request $request)
    {
        $id = PaymentSetting::first();
        $data = $request->all();
        $data['stripe'] = 1;
        $id->update($data);
        return redirect()->back()->with('msg','setting changed successfully..!!');
    }

    public function update_paypal(Request $request)
    {
        $id = PaymentSetting::first();
        $data = $request->all();
        $data['paypal'] = 1;
        $id->update($data);
        return redirect()->back()->with('msg','setting changed successfully..!!');
    }

    public function update_razorpay(Request $request)
    {
        $id = PaymentSetting::first();
        $data = $request->all();
        $data['razorpay'] = 1;
        $id->update($data);
        return redirect()->back()->with('msg','setting changed successfully..!!');
    }

    public function order_setting()
    {
        $currency_symbol = GeneralSetting::first()->currency_symbol;
        $orderData = OrderSetting::first();
        return view('admin.setting.order_setting',compact('currency_symbol','orderData'));
    }

    public function update_order_setting(Request $request)
    {
        $id = OrderSetting::first();
        $data = $request->all();
        $master = [];
        $min_value = $data['min_value'];
        for ($i=0; $i < count($min_value); $i++)
        {
            $temp['min_value'] = $min_value[$i];
            $temp['max_value'] = $data['max_value'][$i];
            $temp['charges'] = $data['charges'][$i];
            array_push($master,$temp);
        }
        $data['charges'] = json_encode($master);
        $data['order_assign_manually'] = $request->has('order_assign_manually') ? 0 : 1;
        $id->update($data);
        return redirect()->back()->with('msg','setting changed successfully..!!');
    }

    public function delivery_person_setting()
    {
        $currency_symbol = GeneralSetting::first()->currency_symbol;
        $general_setting = GeneralSetting::first();
        return view('admin.setting.delivery_person_setting',compact('currency_symbol','general_setting'));
    }

    public function update_delivery_person_setting(Request $request)
    {
        $request->validate(['driver_auto_refrese' => 'gt:29'],
        [
            'driver_auto_refrese.gt' => 'The Driver Auto Refrese Must Be Greater Than Or Equals To 30.',
        ]);
        $data = $request->all();
        $id = GeneralSetting::first();
        $master = [];
        $earning = [];
        $vehical_type = $data['vehical_type'];
        for ($i=0; $i < count($vehical_type); $i++)
        {
            $temp['vehical_type'] = $vehical_type[$i];
            $temp['license'] = $data['license'][$i];
            array_push($master,$temp);
        }
        $data['driver_vehical_type'] = json_encode($master);

        $min_km = $data['min_km'];
        for ($i=0; $i < count($min_km); $i++)
        {
            $temp1['min_km'] = $min_km[$i];
            $temp1['max_km'] = $data['max_km'][$i];
            $temp1['charge'] = $data['charge'][$i];
            array_push($earning,$temp1);
        }
        $data['driver_earning'] = json_encode($earning);
        $data['is_driver_accept_multipleorder'] = $request->has('is_driver_accept_multipleorder') ? 1 : 0;
        $id->update($data);
        return redirect()->back()->with('msg','Setting update successfully..!!');
    }

    public function static_pages()
    {
        $setting = GeneralSetting::first();
        return view('admin.setting.static_pages',compact('setting'));
    }

    public function update_privacy(Request $request)
    {
        GeneralSetting::find(1)->update(['privacy_policy' => $request->privacy_policy]);
        return redirect()->back()->with('msg','setting changed successfully..!!');
    }

    public function update_terms(Request $request)
    {
        GeneralSetting::find(1)->update(['terms_and_condition' => $request->terms_and_condition]);
        return redirect()->back()->with('msg','setting changed successfully..!!');
    }

    public function update_help(Request $request)
    {
        GeneralSetting::find(1)->update(['help' => $request->help]);
        return redirect()->back()->with('msg','setting changed successfully..!!');
    }

    public function update_about(Request $request)
    {
        GeneralSetting::find(1)->update(['about_us' => $request->about_us]);
        return redirect()->back()->with('msg','setting changed successfully..!!');
    }

    public function update_company_details(Request $request)
    {
        GeneralSetting::find(1)->update(['company_details' => $request->company_details]);
        return redirect()->back()->with('msg','setting changed successfully..!!');
    }

    public function update_status(Request $request)
    {
        $id = PaymentSetting::first();
        $id->update($request->all());
        return response(['success' => true]);
    }

    public function verification_setting ()
    {
        $currency_symbol = GeneralSetting::first()->currency_symbol;
        $general_setting = GeneralSetting::first();
        return view('admin.setting.verification_setting',compact('general_setting'));
    }

    public function update_verification_seting(Request $request)
    {
        $id = GeneralSetting::first();
        $data = $request->all();
        if(isset($data['verification']))
        {
            if(isset($data['verification_email']) || isset($data['verification_phone']))
            {
                $data['verification'] = 1;
                $data['verification_email'] = $request->has('verification_email') ? 1 : 0;
                $data['verification_phone'] = $request->has('verification_phone') ? 1 : 0;
            }
            else
                return redirect()->back()->withErrors('At least select one mail or sms');
        }
        else
        {
            $data['verification'] = 0;
            $data['verification_email']  = 0;
            $data['verification_phone']  = 0;
        }
        $id->update($data);
        return redirect()->back()->with('msg','update change successfully..!!');
    }

    public function update_version_setting(Request $request)
    {
        $data = $request->all();
        $id = GeneralSetting::first();
        $id->update($data);
        return redirect()->back()->with('msg','update change successfully..!!');
    }

    public function update_noti(Request $request)
    {
        $id = GeneralSetting::first();
        $id->update($request->all());
        return response(['success' => true]);
    }

    public function license_setting()
    {
        $general_setting = GeneralSetting::first();
        return view('admin.setting.license_setting',compact('general_setting'));
    }

    public function update_license(Request $request)
    {
        $request->validate([
            'license_code' => 'required',
            'client_name' => 'required'
        ]);
        $api = new LicenseBoxExternalAPI();
        $result = $api->activate_license($request->license_code, $request->client_name);
        if ($result['status'] == true)
        {
            $id = GeneralSetting::find(1);
            $data = $request->all();
            $data['license_verify'] = 1;
            $id->update($data);
            return redirect('/');
        }
        else
        {
            return redirect()->back()->with('error_msg', $result['message']);
        }
        return redirect('admin/setting');
    }

    public function updateENV($data)
    {
        $envFile = app()->environmentFilePath();
        if ($envFile)
        {
            $str = file_get_contents($envFile);
            if (count($data) > 0) {
                foreach ($data as $envKey => $envValue) {
                    $str .= "\n"; // In case the searched variable is in the last line without \n
                    $keyPosition = strpos($str, "{$envKey}=");
                    $endOfLinePosition = strpos($str, "\n", $keyPosition);
                    $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                    // If key does not exist, add it
                    if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                        $str .= "{$envKey}={$envValue}\n";
                    } else {
                        $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                    }
                }
            }
            $str = substr($str, 0, -1);
            if (!file_put_contents($envFile, $str)) {
            }
            return true;
        }
    }

    public function testMail(Request $request)
    {
        try {
            $setting = GeneralSetting::first();
            $subject = 'Test Mail From Admin Panel';
            $message = 'This is a test email sent from the admin panel to ensure the proper configuration';
            $config = array(
                'driver'     => $setting->mail_mailer,
                'host'       => $setting->mail_host,
                'port'       => $setting->mail_port,
                'from'       => array('address' => $setting->mail_from_address,'name' => $setting->mail_from_name),
                'encryption' => $setting->mail_encryption,
                'username'   => $setting->mail_username,
                'password'   => $setting->mail_password
            );
            Config::set('mail', $config);
            Mail::to($request->to)->send(new TestMail($message, $subject, $setting->business_name));
            return response()->json(['success' => true, 'message' => 'Mail Sent Successfully!'], 200);
        } catch (Exception $e) {
            $error = $e->getMessage();
            return response()->json(['success' => false, 'message' => $error]);
        }
    }

    public function check_sms(Request $request)
    {
        try {
            $request->validate([
                'sms_to' => 'required',
                'message' => 'required'
            ]);
            $data = $request->all();
            $setting = GeneralSetting::find(1);
            $sid = $setting->twilio_acc_id;
            $token = $setting->	twilio_auth_token;
            $client = new Client($sid, $token);
            $message = $client->messages->create(
                $data['sms_to'],
                [
                    'from' => $setting->twilio_phone_no,
                    'body' => $data['message']
                ]
            );
            return redirect()->back()->with('msg', 'Message Sent Successfully');
        } catch(Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

}
