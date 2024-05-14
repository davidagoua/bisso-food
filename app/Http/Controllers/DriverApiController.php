<?php

namespace App\Http\Controllers;

use App\Mail\StatusChange;
use App\Models\DeliveryPerson;
use App\Models\GeneralSetting;
use App\Models\NotificationTemplate;
use Illuminate\Http\Request;
use Auth;
use DB;
use Hash;
use Mail;
use Twilio\Rest\Client;
use App\Mail\Verification;
use App\Models\DeliveryZone;
use App\Models\DeliveryZoneArea;
use App\Models\Faq;
use App\Models\Notification;
use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Settle;
use Carbon\Carbon;

class DriverApiController extends Controller
{
    public function apiDriverLogin(Request $request)
    {
        $request->validate([
            'email_id' => 'bail|required|email',
            'password' => 'required|min:6'
        ]);

        $user = ([
            'email_id' => $request->email_id,
            'password' => $request->password,
        ]);

        if(Auth::guard('driver')->attempt($user))
        {
            $user = Auth::guard('driver')->user();
            if($user->status == 1)
            {
                if(isset($request->device_token))
                {
                    $user->device_token = $request->device_token;
                    $user->save();
                }
                if($user['is_verified'] == 1)
                {
                    $user['token'] =  $user->createToken('food delivery')->accessToken;
                    $user['national_identity'] = url('images/upload').'/'.$user->national_identity;
                    $user['licence_doc'] = url('images/upload').'/'.$user->licence_doc;
                    return response()->json(['success' => true , 'data' => $user], 200);
                }
                else
                {
                    $admin_verify_user = GeneralSetting::find(1)->verification;
                    if($admin_verify_user == 1)
                    {
                        $otp = mt_rand(1000, 9999);

                        $sms_verification = GeneralSetting::first()->verification_phone;
                        $mail_verification = GeneralSetting::first()->verification_email;

                        $verification_content = NotificationTemplate::where('title','verification')->first();

                        $msg_content = $verification_content->notification_content;
                        $mail_content = $verification_content->mail_content;

                        $sid = GeneralSetting::first()->twilio_acc_id;
                        $token = GeneralSetting::first()->twilio_auth_token;

                        $detail['otp'] = $otp;
                        $detail['user_name'] = $user->name;
                        $detail['app_name'] = GeneralSetting::first()->business_name;
                        $data = ["{otp}", "{user_name}"];

                        $user->otp = $otp;
                        $user->save();
                        $user['national_identity'] = url('images/upload').'/'.$user->national_identity;
                        $user['licence_doc'] = url('images/upload').'/'.$user->licence_doc;
                        if($mail_verification == 1)
                        {
                            $message1 = str_replace($data, $detail, $mail_content);
                            try
                            {
                                Mail::to($user->email_id)->send(new Verification($message1));
                            }
                            catch (\Throwable $th)
                            {
                                //throw $th;
                            }
                        }
                        if($sms_verification == 1)
                        {
                            try
                            {
                                $phone = $user->phone_code.$user->contact;
                                $message1 = str_replace($data, $detail, $msg_content);
                                $client = new Client($sid, $token);
                                $client->messages->create(
                                    $phone,
                                    array(
                                        'from' => GeneralSetting::first()->twilio_phone_no,
                                        'body' => $message1
                                    )
                                );
                            }
                            catch (\Throwable $th) {}
                        }
                        return response(['success' => true ,'data' => $user, 'msg' => 'Otp send in your account']);
                    }
                }
            }
            else
            {
                return response(['success' => false ,'data' => 'you are disable by admin please contact admin']);
            }
        }
        else
        {
            return response(['success' => false , 'data' => 'this crediantial does not match our record']);
        }
    }

    public function apiDriverCheckOtp(Request $request)
    {
        $request->validate([
            'driver_id' => 'bail|required',
            'otp' => 'bail|required|min:4',
        ]);

        $user = DeliveryPerson::find($request->driver_id);
        if($user)
        {
            if($user->otp == $request->otp)
            {
                $user->is_verified = 1;
                $user->save();
                $user['token'] = $user->createToken('food delivery')->accessToken;
                return response(['success' => true ,'data' => $user ,'msg' => 'SuccessFully verify your account...!!']);
            }
            else
            {
                return response(['success' => false , 'data' => 'Something went wrong otp does not match..!']);
            }
        }
        else
        {
            return response(['success' => false , 'data' => 'Oops...user not found..!!']);
        }
    }

    public function apiDriverRegister(Request $request)
    {
        $request->validate([
            'first_name' => 'bail|required',
            'last_name' => 'bail|required',
            'email_id' => 'bail|required|unique:delivery_person',
            'password' => 'bail|min:6',
            'phone' => 'bail|required|numeric|digits_between:6,12',
            'phone_code' => 'bail|required'
        ]);

        $admin_verify_user = GeneralSetting::find(1)->verification;
        if($admin_verify_user == 1)
        {
            $is_verified = 0;
        }
        else
        {
            $is_verified = 1;
        }

        $delivery_person = DeliveryPerson::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email_id' => $request->email_id,
            'image' => 'product_default.jpg',
            'contact' => $request->phone,
            'phone_code' => $request->phone_code,
            'status' => 1,
            'is_online' => 1,
            'notification' => 1,
            'is_verified' => $is_verified,
            'password' => Hash::make($request->password),
        ]);

        if($delivery_person['is_verified'] == 1)
        {
            $delivery_person['token'] =  $delivery_person->createToken('food delivery')->accessToken;
            return response()->json(['success' => true , 'data' => $delivery_person , 'msg' => 'account created successfully..!!'], 200);
        }
        else
        {
            $admin_verify_user = GeneralSetting::find(1)->verification;
            if($admin_verify_user == 1)
            {
                $otp = mt_rand(1000, 9999);

                $sms_verification = GeneralSetting::first()->verification_phone;
                $mail_verification = GeneralSetting::first()->verification_email;

                $verification_content = NotificationTemplate::where('title','verification')->first();

                $msg_content = $verification_content->notification_content;
                $mail_content = $verification_content->mail_content;

                $sid = GeneralSetting::first()->twilio_acc_id;
                $token = GeneralSetting::first()->twilio_auth_token;

                $detail['otp'] = $otp;
                $detail['user_name'] = $delivery_person->name;
                $detail['app_name'] = GeneralSetting::first()->business_name;
                $data = ["{otp}", "{user_name}"];

                $delivery_person->otp = $otp;
                $delivery_person->save();
                if($mail_verification == 1)
                {
                    $message1 = str_replace($data, $detail, $mail_content);
                    try
                    {
                        Mail::to($delivery_person->email_id)->send(new Verification($verification_content,$detail));
                    }
                    catch (\Throwable $th)
                    {
                        //throw $th;
                    }
                }
                if($sms_verification == 1)
                {
                    try
                    {
                        $phone = $delivery_person->phone_code.$delivery_person->contact;
                        $message1 = str_replace($data, $detail, $msg_content);
                        $client = new Client($sid, $token);
                        $client->messages->create(
                            $phone,
                            array(
                                'from' => GeneralSetting::first()->twilio_phone_no,
                                'body' => $message1
                            )
                        );
                    }
                    catch (\Throwable $th) {}
                }
                return response(['success' => true ,'data' => $delivery_person, 'msg' => 'your account created successfully please verify your account']);
            }
        }
    }

    public function apiForgotPasswordOtp(Request $request)
    {
        $request->validate([
            'email_id' => 'bail|required|email',
        ]);

        $user = DeliveryPerson::where('email_id',$request->email_id)->first();
        if($user)
        {
            $otp = mt_rand(1000, 9999);
            $verification_content = NotificationTemplate::where('title','verification')->first();
            $mail_content = $verification_content->mail_content;

            $detail['otp'] = $otp;
            $detail['user_name'] = $user->name;
            $detail['app_name'] = GeneralSetting::first()->business_name;
            $data = ["{otp}","{user_name}","{app_name}"];
            $user->otp = $otp;
            $user->save();
            $message1 = str_replace($data, $detail, $mail_content);
            try
            {
                Mail::to($user->email_id)->send(new Verification($message1));
            }
            catch (\Throwable $th)
            {
                //throw $th;
            }
            return response(['success' => true , 'data' => $user->id ,'msg' => 'verification mail send into your mail']);
        }
        else
        {
            return response(['success' => false , 'data' => 'Oops.. driver not found...!!']);
        }
    }

    public function apiForgotPasswordCheckOtp(Request $request)
    {
        $request->validate([
            'driver_id' => 'bail|required',
            'otp' => 'bail|required|min:4',
        ]);

        $user = DeliveryPerson::find($request->driver_id);
        if($user)
        {
            if($user->otp == $request->otp)
            {
                return response(['success' => true ,'data' => $user->id ,'msg' => 'Otp match']);
            }
            else
            {
                return response(['success' => false , 'data' => 'Something went wrong otp does not match..!']);
            }
        }
    }

    public function apiForgotPassword(Request $request)
    {
        $request->validate([
            'password' => 'bail|required|min:6',
            'password_confirmation' => 'bail|required|min:6|same:password',
            'user_id' => 'bail|required'
        ]);
        $data = $request->all();
        $user = DeliveryPerson::find($request['user_id']);
        if($user)
        {
            $user->password = Hash::make($data['password']);
            $user->save();
            return response(['success' => true , 'data' => 'Password Update Successfully...!!']);
        }
    }

    /****** send otp & forgot password ***/
    public function apiReSendOtp(Request $request)
    {
        $request->validate([
            'email_id' => 'required_without:phone',
            'phone' => 'required_without:email_id',
        ]);
        $user = DeliveryPerson::where('email_id',$request->email_id)->first();
        if($user)
        {
            $otp = mt_rand(1000, 9999);
            $user->otp = $otp;
            $user->save();
            $verification_content = NotificationTemplate::where('title','verification')->first();

            $mail_content = $verification_content->mail_content;

            $detail['otp'] = $otp;
            $detail['user_name'] = $user->name;
            $detail['app_name'] = GeneralSetting::first()->business_name;
            $data = ["{otp}", "{user_name}"];
            $message1 = str_replace($data, $detail, $mail_content);
            try
            {
                Mail::to($user->email_id)->send(new Verification($message1));
            }
            catch (\Throwable $th)
            {
                //throw $th;
            }
            return response(['success' => true , 'data' => $user]);
        }
        else
        {
            return response(['success' => false , 'data' => 'Ooops user not found..!!']);
        }
    }

    public function apiDeliveryZone()
    {
        $vendor_driver = $this->checkVendorDriver();
        if($vendor_driver == 1)
        {
            $delivery_zones = DeliveryZone::where('status',1)->get(['id','name']);
            return response(['success' => true , 'data' => $delivery_zones]);
        }
        else
        {
            return response(['success' => true , 'data' => 'not a global driver']);
        }
    }

    public function apiSetLocation(Request $request)
    {
        $vendor_driver = $this->checkVendorDriver();
        if($vendor_driver == 1)
        {
            $id = auth()->user();
            $id->delivery_zone_id = $request->delivery_zone_id;
            $id->save();
            return response(['success' => true , 'data' => 'successfully Set this location']);
        }
        else
        {
            return response(['success' => false , 'data' => 'vendor driver']);
        }
    }

    public function apiDriverOrder()
    {
        (new CustomController)->cancel_max_order();
        (new CustomController)->driver_cancel_max_order();
        $vendor_driver = $this->checkVendorDriver();
        $driver = auth()->user();
        if($vendor_driver == 1) 
        {
            $delivery_zone_areas = DeliveryZoneArea::where('delivery_zone_id',$driver->delivery_zone_id)->get();
            $users = array();
            $vendors = array();
            foreach ($delivery_zone_areas as $delivery_zone_area)
            {
                $a = explode(',',$delivery_zone_area->vendor_id);
                foreach ($a as $value)
                {
                    if($value)
                    {
                        $near_vendors = Vendor::find($value)->GetByDistance($delivery_zone_area->lat, $delivery_zone_area->lang, $delivery_zone_area->radius)->get();
                        if ($near_vendors != null)
                        {
                            foreach ($near_vendors as $near_vendor)
                            {
                                array_push($vendors,$near_vendor->id);
                            }
                        }
                    }
                    $near_users = DB::select(DB::raw('SELECT user_id, ( 3959 * acos( cos( radians(' . $delivery_zone_area->lat . ') ) * cos( radians( lat ) ) * cos( radians( lang ) - radians(' . $delivery_zone_area->lang . ') ) + sin( radians(' . $delivery_zone_area->lat . ') ) * sin( radians(lat) ) ) ) AS distance FROM user_address HAVING distance < ' . $delivery_zone_area->radius . ' ORDER BY distance'));
                    foreach($near_users as $near_user)
                    {
                        array_push($users,$near_user->user_id);
                    }
                }
            }
            $orders = Order::whereIn('user_id',$users)->whereIn('vendor_id',$vendors)->where('delivery_type','HOME')->where('order_status','APPROVE')->get(['id','address_id','order_id','user_id','vendor_id','payment_status','address_id','order_status','amount','payment_type']);
            return response(['success' => true , 'data' => $orders]);
        }
        else
        {
            $orders = Order::where([['delivery_person_id',$driver->id],['order_status','APPROVE']])->get(['id','address_id','order_id','user_id','payment_status','address_id','order_status','vendor_id','amount','payment_type']);
            return response(['success' => true , 'data' => $orders]);
        }
    }

    public function apiStatusChange(Request $request)
    {
        (new CustomController)->cancel_max_order();
        (new CustomController)->driver_cancel_max_order();
        $request->validate([
            'order_id' => 'required',
            'order_status' => 'required',
        ]);

        $reqData = $request->all();
        $order = Order::find($reqData['order_id']);
        if($order)
        {
            $vendor_driver = $this->checkVendorDriver();
            if($vendor_driver == 1)
            {
                $order->order_status = $reqData['order_status'];
                $order->save();
                if($reqData['order_status'] == 'ACCEPT')
                {
                    if($order->delivery_person_id !== null)
                    {
                        return response(['success' => false , 'data' => 'Order has already been accepted.']);
                    }
                    $order->delivery_person_id = auth()->user()->id;
                    $order->save();
                }
                if($reqData['order_status'] == 'COMPLETE')
                {
                    $order->payment_status = 1;
                    $order->save();
                    $vendor = Vendor::find($order->vendor_id);
                    $distance = $this->distance($vendor->lat,$vendor->lang,$order->user_address['lat'],$order->user_address['lang'],'K');
                    $earnings = json_decode(GeneralSetting::first()->driver_earning);
                    $earn = 0;
                    foreach ($earnings as $earning)
                    {
                        if(($distance > $earning->min_km) && ($distance <= $earning->max_km))
                        {
                            $earn = $earning->charge;
                        }
                    }
                    if($earn == 0)
                    {
                        $earn = max(array_column($earnings, 'charge'));
                    }

                    $settle = array();
                    $settle['vendor_id'] = $vendor->id;
                    $settle['order_id'] = $order->id;
                    $settle['driver_id'] = auth()->user()->id;
                    if ($order->payment_type == 'COD') 
                    {
                        $settle['payment'] = 0;
                    } else {
                        $settle['payment'] = 1;
                    }
                    $settle['vendor_status'] = 0;
                    $settle['driver_status'] = 0;
                    $settle['admin_earning'] = $order->admin_commission;
                    $settle['vendor_earning'] = $order->vendor_amount;
                    $settle['driver_earning'] = $earn;
                    Settle::create($settle);
                }
            }

            if($reqData['order_status'] == 'CANCEL')
            {
                $request->validate([
                    'cancel_reason' => 'required',
                ]);
                $order->cancel_by = 'driver';
                $order->delivery_person_id = auth()->user()->id;
                $order->cancel_reason = $request->cancel_reason;
                $order->save();
            }

            $user = User::find($order->user_id);
            $status_change = NotificationTemplate::where('title','change status')->first();
            if ($user->language != 'spanish')
            {
                $mail_content = $status_change->mail_content;
                $notification_content = $status_change->notification_content;
            }
            else
            {
                $mail_content = $status_change->spanish_mail_content;
                $notification_content = $status_change->spanish_notification_content;
            }
            $detail['user_name'] = $user->name;
            $detail['order_id'] = $order->order_id;
            $detail['date'] = $order->date;
            $detail['order_status'] = $order->order_status;
            $detail['company_name'] = GeneralSetting::find(1)->business_name;
            $data = ["{user_name}","{order_id}","{date}","{order_status}","{company_name}"];

            $message1 = str_replace($data, $detail, $notification_content);
            $mail = str_replace($data, $detail, $mail_content);
            if(GeneralSetting::find(1)->customer_notification == 1)
            {
                if($user->device_token != null)
                {
                    try {
                        OneSignal::sendNotificationToUser(
                            $message1,
                            $user->device_token,
                            $url = null,
                            $data = null,
                            $buttons = null,
                            $schedule = null,
                            GeneralSetting::find(1)->business_name
                        );
                    } catch (\Throwable $th) {

                    }
                }

                try {
                    Mail::to($user->email_id)->send(new StatusChange($mail));
                } catch (\Throwable $th) {

                }
            }
            $notification = array();
            $notification['user_id'] = $user->id;
            $notification['user_type'] = 'user';
            $notification['title'] = $reqData['order_status'];
            $notification['message'] = $message1;
            Notification::create($notification);
            $order = Order::find($reqData['order_id'])->id;
            return response(['success' => true , 'data' => $order ,'msg' => 'status changed']);
        }
        else
        {
            return response(['success' => false , 'msg' => 'order not found']);
        }
    }

    public function apiDriver()
    {
        $id = auth()->user();
        $id->national_identity = url('images/upload').'/'.$id->national_identity;
        $id->licence_doc = url('images/upload').'/'.$id->licence_doc;
        return response(['success' => true , 'data' => $id]);
    }

    public function apiUpdateDriver(Request $request)
    {
        $data = $request->all();
        $id = auth()->user();
        $id->update($data);
        return response(['success'=> true , 'data' => 'Update Successfully']);
    }

    public function apiDriverImage(Request $request)
    {
        $request->validate([
            'image' => 'required'
        ]);
        $id = auth()->user();
        if(isset($request->image))
        {
            $img = $request->image;
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data1 = base64_decode($img);
            $Iname = uniqid();
            $file = public_path('/images/upload/') . $Iname . ".png";
            $success = file_put_contents($file, $data1);
            $data['image'] = $Iname . ".png";
        }
        $id->update($data);
        return response(['success' => true , 'data' => 'image updated succssfully..!!']);
    }

    public function apiOrderHistory()
    {
        (new CustomController)->cancel_max_order();
        (new CustomController)->driver_cancel_max_order();
        $orders['complete'] = Order::where([['delivery_person_id',auth()->user()->id],['order_status','COMPLETE']])->get(['id','address_id','amount','order_id','user_id','vendor_id','payment_status','order_status']);
        $orders['cancel'] = Order::where([['delivery_person_id',auth()->user()->id],['order_status','CANCEL']])->get(['id','address_id','amount','order_id','cancel_reason','user_id','vendor_id','payment_status','order_status']);
        return response(['success' => true , 'data' => $orders]);
    }

    public function apiOrderEarning()
    {
        $vendor_driver = $this->checkVendorDriver();
        if ($vendor_driver == 1)
        {
            $settles = Settle::where('driver_id',auth()->user()->id)->get();
            $data = [];
            foreach ($settles as $value)
            {
                $order = Order::find($value->order_id);
                $temp['earning'] = $value->driver_earning;
                $temp['order_id'] = $order->order_id;
                $temp['user_name'] = $order->user['name'];
                $temp['date'] = $order->date;
                $temp['time'] = $order->time;
                array_push($data,$temp);
            }
            $total_amount = $settles = Settle::where('driver_id',auth()->user()->id)->sum('driver_earning');
            return response(['success' => true , 'data' => $data , 'total_earning' => $total_amount]);
        }
        else
        {
            return response(['success' => false , 'data' => 'vendor driver']);
        }
    }

    public function apiEarningHistory()
    {
        (new CustomController)->cancel_max_order();
        (new CustomController)->driver_cancel_max_order();
        $vendor_driver = $this->checkVendorDriver();
        if ($vendor_driver == 1)
        {
            $data = [];
            $data['current_month'] = strval(Settle::where('driver_id',auth()->user()->id)->whereMonth('created_at', Carbon::now()->month)->sum('driver_earning'));
            $data['today_earning'] = strval(Settle::where('driver_id',auth()->user()->id)->whereDate('created_at', Carbon::today())->sum('driver_earning'));
            $data['week_earning'] = strval(Settle::where('driver_id',auth()->user()->id)->whereBetween('created_at', [Carbon::now()->subDays(7)->format('Y-m-d')." 00:00:00",  Carbon::now()->format('Y-m-d')." 23:59:59"])->sum('driver_earning'));
            $data['yearliy_earning'] = strval(Settle::where('driver_id',auth()->user()->id)->whereYear('created_at', date('Y'))->sum('driver_earning'));           
            $data['total_amount'] = strval(Settle::where('driver_id',auth()->user()->id)->sum('driver_earning'));
            $setting = GeneralSetting::first();
            $now = Carbon::today();
            $g_data = [];
            for ($i = 0; $i < 12; $i++)
            {
                $count_month = 0;
                $graphs = Order::where([['delivery_type','HOME'],['delivery_person_id',auth()->user()->id]])->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->get();
                foreach ($graphs as $graph)
                {
                    if (isset($graph->vendor['lat']) && isset($graph->vendor['lang']) && isset($graph->user_address['lat']) && isset($graph->user_address['lang']))
                    {
                        $distance = $this->distance($graph->vendor['lat'],$graph->vendor['lang'],$graph->user_address['lat'],$graph->user_address['lang']);
                        $charges = json_decode($setting->driver_earning);
                        foreach($charges as $charge)
                        {
                            if(($distance > $charge->min_km) && ($distance <= $charge->max_km))
                            {
                                $count_month += $charge->charge;
                            }
                        }
                    }
                }
                $temp['month'] = $now->format('M');
                $temp['month_earning'] = strval($count_month);
                array_push($g_data,$temp);
                $now = $now->subMonth();
            }

            $data['graph'] = $g_data;
            return response(['success' => true , 'data' => $data]);
        }
        else
        {
            return response(['success' => false , 'data' => 'vendor driver']);
        }
    }

    public function distance($lat1,$lang1,$lat2,$lang2)
    {
        $lat1 = $lat1;
        $lon1 = $lang1;
        $lat2 = $lat2;
        $lon2 = $lang2;
        $unit = 'K';
        if (($lat1 == $lat2) && ($lon1 == $lon2))
        {
            $distance =  0;
        }
        else
        {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);
            if ($unit == "K")
            {
                $distance = $miles * 1.609344;
            }
            else if ($unit == "N")
            {
                $distance = $miles * 0.8684;
            }
            else
            {
                $distance = $miles;
            }
        }

        return $distance;
    }

    public function apiDeliveryPersonChangePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'bail|required|min:6',
            'password' => 'bail|required|min:6',
            'password_confirmation' => 'bail|required|min:6',
        ]);
        $data = $request->all();
        $id = auth()->user();
        if(Hash::check($data['old_password'], $id->password) == true)
        {
            if($data['password'] == $data['password_confirmation'])
            {
                $id->password = Hash::make($data['password']);
                $id->save();
                return response(['success' => true , 'data' => 'Password Update Successfully...!!']);
            }
            else
            {
                return response(['success' => false , 'data' => 'password and confirm password does not match']);
            }
        }
        else
        {
            return response(['success' => false , 'data' => 'Old password does not match']);
        }
    }

    public function apiDriverFaq()
    {
        $data = Faq::where('type','driver')->get();
        return response(['success' => true , 'data' => $data]);
    }

    public function apiUpdateVehical(Request $request)
    {
        $data = $request->all();
        $id = auth()->user();

        if(isset($request->national_identity))
        {
            $img = $request->national_identity;
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data1 = base64_decode($img);
            $Iname = uniqid();
            $file = public_path('/images/upload/') . $Iname . ".png";
            $success = file_put_contents($file, $data1);
            $data['national_identity'] = $Iname . ".png";
        }
        if(isset($request->licence_doc))
        {
            $img = $request->licence_doc;
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data1 = base64_decode($img);
            $Iname = uniqid();
            $file = public_path('/images/upload/') . $Iname . ".png";
            $success = file_put_contents($file, $data1);
            $data['licence_doc'] = $Iname . ".png";
        }
        $id->update($data);
        return response(['success' => true , 'data' => 'update successfully.!!']);
    }

    public function apiDriverSetting()
    {
        $setting = GeneralSetting::where('id',1)->first(['driver_vehical_type','driver_app_id','driver_auth_key','driver_api_key','currency','currency_symbol','company_black_logo','driver_auto_refrese','cancel_reason']);
        if (auth('driverApi')->user() != null)
        {
            $vendor_driver = $this->checkVendorDriver();
            $setting->global_driver = $vendor_driver == 1 ? 'true' : 'false';
        }
        return response(['success' => true , 'data' => $setting]);
    }

    public function apiDriverNotification()
    {
        $data = Notification::where([['user_type','driver'],['user_id',auth()->user()->id]])->get();
        return response(['success' => true , 'data' => $data]);
    }

    public function apiUpdateLatLang(Request $request)
    {
        $request->validate([
            'lat' => 'required',
            'lang' => 'required',
        ]);
        auth()->user()->update($request->all());
        return response(['success' => true , 'data' => 'update successfully..!']);
    }

    public function checkVendorDriver()
    {
        // 1 for global driver , 0 for vendor driver
        $LoginDriver = Auth::guard('driver')->user();
        if(!$LoginDriver)
        {
            $LoginDriver = auth()->user();
            if(!$LoginDriver)
            {
                $LoginDriver = auth('driverApi')->user();
            }
        }
        if ($LoginDriver) {
            return $LoginDriver->vendor_id == null ? 1 : 0;
        }
    }

    public function apiSingleOrder()
    {
        $driver = Auth()->user()->id;
        $order = Order::whereNotIn('order_status',['COMPLETE','CANCEL','REJECT'])->where('delivery_person_id',$driver)->first();
        return response(['success' => true , 'data' => $order]);
    }
}