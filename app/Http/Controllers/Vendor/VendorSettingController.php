<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Mail\Verification;
use App\Models\Country;
use App\Models\Cuisine;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Order;
use App\Models\WorkingHours;
use App\Models\GeneralSetting;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Gate;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\Review;
use App\Models\Settle;
use App\Models\DeliveryPerson;
use App\Models\Submenu;
use App\Models\OrderChild;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Stripe\Service\OrderReturnService;
use Twilio\Rest\Client;
use Session;

class VendorSettingController extends Controller
{
    public function login()
    {
        return view('vendor.vendor_login');
    }
    public function vendor_confirm_login(Request $request)
    {
        $request->validate([
            'email_id' => 'bail|required|email',
            'password' => 'bail|required',
        ]);

        if(Auth::attempt(['email_id' => request('email_id'), 'password' => request('password')]))
        {
            $user = Auth::user()->load('roles');

            if($user->is_verified == 1)
            {
                if ($user->roles->contains('title', 'vendor'))
                {
                    $vendor = Vendor::where('user_id',auth()->user()->id)->first();

                    if($vendor->status == 1)
                    {
                        if ($user->roles->contains('title', 'vendor'))
                        {
                            if ($vendor->vendor_own_driver == 1)
                            {
                                Session::put('vendor_driver', 1);
                            }
                            else
                            {
                                Session::put('vendor_driver', 0);
                            }
                            return redirect('vendor/vendor_home');
                        }
                        else
                        {
                            return redirect()->back()->withErrors('Invalid Email Or Password.')->withInput();
                        }
                    }
                    else
                    {
                        return redirect()->back()->withErrors('You disable by admin please contact admin.')->withInput();
                    }
                }
                else
                {
                    return redirect()->back()->withErrors('Only vendor can login.')->withInput();
                }
            }
            else
            {
                return redirect('vendor/send_otp/'.$user->id);
            }
        }
        else
        {
            return redirect()->back()->withErrors('Invalid Email Or Password.')->withInput();
        }
    }


    public function orderChart(Request $request)
    {
        $vendor = Vendor::where('user_id',auth()->user()->id)->first();
        $masterYear = array();
        $labelsYear = array();

        array_push($masterYear, Order::where('vendor_id',$vendor->id)->whereMonth('created_at', Carbon::now())->count());
        for ($i = 1; $i <= 11; $i++) {
            if ($i >= Carbon::now()->month)
            {
                array_push($masterYear, Order::where('vendor_id',$vendor->id)->whereMonth('created_at',Carbon::now()->subMonths($i))->whereYear('created_at', Carbon::now()->subYears(1))->count());
            } else {
                array_push($masterYear, Order::where('vendor_id',$vendor->id)->whereMonth('created_at', Carbon::now()->subMonths($i))
                ->whereYear('created_at', Carbon::now()->year)
                ->count());
            }
        }

        array_push($labelsYear, Carbon::now()->format('M-y'));
        for ($i = 1; $i <= 11; $i++) {
            array_push($labelsYear, Carbon::now()->subMonths($i)->format('M-y'));
        }
        return ['data' => $masterYear, 'label' => $labelsYear];
    }

    public function revenueChart()
    {
        $userYear = array();
        $Userlabels = array();
        $vendor = Vendor::where('user_id',auth()->user()->id)->first();

        array_push($userYear, Settle::where([['vendor_id',$vendor->id],['vendor_status',1]])->whereMonth('created_at', Carbon::now())->sum('vendor_earning'));
        for ($i = 1; $i <= 11; $i++)
        {
            if ($i >= Carbon::now()->month)
            {
                array_push($userYear, Settle::where([['vendor_id',$vendor->id],['vendor_status',1]])->whereMonth('created_at',Carbon::now()->subMonths($i))
                ->whereYear('created_at', Carbon::now()->subYears(1))
                ->sum('vendor_earning'));
            }
            else
            {
                array_push($userYear, Settle::where([['vendor_id',$vendor->id],['vendor_status',1]])->whereMonth('created_at', Carbon::now()->subMonths($i))
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('vendor_earning'));
            }
        }

        array_push($Userlabels, Carbon::now()->format('M-y'));
        for ($i = 1; $i <= 11; $i++) {
            array_push($Userlabels, Carbon::now()->subMonths($i)->format('M-y'));
        }
        return ['data' => $userYear, 'label' => $Userlabels];
    }

    public function vendorAvarageTime()
    {
        $lastMonthTotalOrder = [];
        $LastMonthavarageTime = [];
        $currentMonthTotalOrder = [];
        $currentMonthAvarageTime = [];
        $vendor = Vendor::where('user_id',auth()->user()->id)->first();
        $lastMonthOrders = Order::where([['vendor_id',$vendor->id],['order_status','COMPLETE']])->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->get();
        $currentMonthOrders = Order::where([['vendor_id',$vendor->id],['order_status','COMPLETE']])->whereMonth('created_at', Carbon::now()->month)->get();
        foreach ($lastMonthOrders as $lastMonthOrder)
        {
            if ($lastMonthOrder->order_start_time != null && $lastMonthOrder->order_end_time != null)
            {
                $diff_in_minutes = Carbon::parse($lastMonthOrder->order_start_time)->diffInMinutes(Carbon::parse($lastMonthOrder->order_end_time));
                array_push($lastMonthTotalOrder,$diff_in_minutes);
            }
        }
        if (count($lastMonthTotalOrder) > 0)
        {
            $lastMonthOrderSum = array_sum($lastMonthTotalOrder);
            $temp = intval($lastMonthOrderSum) / intval(count($lastMonthTotalOrder));
            array_push($LastMonthavarageTime,intval($temp));
        }

        foreach ($currentMonthOrders as $currentMonthOrder)
        {
            if ($currentMonthOrder->order_start_time != null && $currentMonthOrder->order_end_time != null)
            {
                $diff_in_minutes = Carbon::parse($currentMonthOrder->order_start_time)->diffInMinutes(Carbon::parse($currentMonthOrder->order_end_time));
                array_push($currentMonthTotalOrder,$diff_in_minutes);
            }
        }
        if (count($currentMonthTotalOrder) > 0)
        {
            $currentMonthOrderSum = array_sum($currentMonthTotalOrder);
            $temp = intval($currentMonthOrderSum) / intval(count($currentMonthTotalOrder));
            array_push($currentMonthAvarageTime,intval($temp));
        }
        return ['currentMonth' => $currentMonthAvarageTime , 'lastMonth' => $lastMonthTotalOrder];
    }

    public function change_password()
    {
        $vendor = vendor::where('user_id',auth()->user()->id)->first();
        return view('vendor.vendor.change_password',compact('vendor'));
    }

    public function update_pwd(Request $request)
    {
        $request->validate([
            'old_password' => 'required|min:6',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password|min:6',
        ],
        [
            'password_confirmation.same' => 'The Confirm New Password Does Not Match With The New Password Field.',
        ]
        );
        $data = $request->all();
        $id = auth()->user();
        if(Hash::check($data['old_password'], $id->password) == true)
        {
            $id->password = Hash::make($data['password']);
            $id->save();
            return redirect('vendor/vendor_home')->with('msg','Password Update Successfully...!!');
        }
        else
        {
            return redirect()->back()->with('message','Old password does not match.');
        }
    }

    public function register_vendor()
    {
        $phone_codes = Country::get();
        return view('vendor.vendor_register',compact('phone_codes'));
    }

    public function register(Request $request)
    {
        $request->validate(
        [
            'name' => 'bail|required',
            'email_id' => 'bail|required|unique:users',
            'password' => 'bail|required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/',
            'phone' => 'bail|required|digits_between:6,12',
            'phone_code' => 'bail|required',
        ],
        [
            'password.regex' => 'The Password Should Be Alphanumeric',
        ],
        );
        $admin_verify_user = GeneralSetting::find(1)->verification;
        $veri = $admin_verify_user == 1 ? 0 : 1;
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $data['status'] = 1;
        $data['image'] = 'noimage.png';
        $data['is_verified'] = $veri;
        $data['phone'] = $request->phone;
        $data['phone_code'] = $request->phone_code;
        $user = User::create($data);
        $role_id = Role::where('title','vendor')->orWhere('title','Vendor')->first();
        $user->roles()->sync($role_id);
        if (isset($data['vendor_own_driver']))
        {
            $vendor_own_driver = 1;
            Session::put('vendor_driver', 1);
        }
        else
        {
            $vendor_own_driver = 0;
            Session::put('vendor_driver', 0);
        }
        $cusinies = Cuisine::whereStatus(1)->get('id');
        $tempIds = [];
        foreach ($cusinies as $value) {
            array_push($tempIds,$value->id);
        }
        $cuisine_id = implode(',',$tempIds);
        $vendor = Vendor::create([
            'name' => $request->name,
            'admin_comission_type' => 'percentage',
            'user_id' => $user->id,
            'email_id' => $user->email_id,
            'image' => 'noimage.png',
            'admin_comission_value' => '33',
            'contact' => $user->phone,
            'status' => 1,
            'isExplorer' => 1,
            'vendor_type' => 'veg',
            'isTop' => 1,
            'vendor_logo' => GeneralSetting::first()->company_black_logo,
            'password' => Hash::make($data['password']),
            'vendor_own_driver' => $vendor_own_driver,
            'time_slot' => '15',
            'vendor_language' => 'english',
            'cuisine_id' => $cuisine_id,
            'lat' => '22.3039',
            'lang' => '70.8022',
            'address' => 'rajkot , gujrat',
        ]);

        $start_time = GeneralSetting::first()->start_time;
        $end_time = GeneralSetting::first()->end_time;
        $days = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
        for($i = 0; $i < count($days); $i++)
        {
            $master = array();
            $temp['start_time'] = $start_time;
            $temp['end_time'] = $end_time;
            array_push($master,$temp);
            $delivery_time['vendor_id'] = $vendor->id;
            $delivery_time['period_list'] = json_encode($master);
            $delivery_time['type'] = 'delivery_time';
            $delivery_time['day_index'] = $days[$i];
            $delivery_time['status'] = 1;
            WorkingHours::create($delivery_time);
        }

        $days = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
        for($i = 0; $i < count($days); $i++)
        {
            $master = array();
            $temp['start_time'] = $start_time;
            $temp['end_time'] = $end_time;
            array_push($master,$temp);
            $pickup['vendor_id'] = $vendor->id;
            $pickup['period_list'] = json_encode($master);
            $pickup['type'] = 'pick_up_time';
            $pickup['day_index'] = $days[$i];
            $pickup['status'] = 1;
            WorkingHours::create($pickup);
        }

        $days = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
        for($i = 0; $i < count($days); $i++)
        {
            $master = array();
            $temp['start_time'] = $start_time;
            $temp['end_time'] = $end_time;
            array_push($master,$temp);
            $selling_time['vendor_id'] = $vendor->id;
            $selling_time['period_list'] = json_encode($master);
            $selling_time['type'] = 'selling_timeslot';
            $selling_time['day_index'] = $days[$i];
            $selling_time['status'] = 1;
            WorkingHours::create($selling_time);
        }

        if($user->is_verified == 1)
        {
            if (Auth::attempt(['email_id' => $user['email_id'], 'password' => $request->password]))
            {
                return redirect('vendor/vendor_home');
            }
        }
        else
        {
            return redirect('vendor/send_otp/'.$user->id);
        }
    }

    public function send_otp($id)
    {
        $otp = mt_rand(1000, 9999);
        $user = User::find($id);
        $user->otp = $otp;
        $user->save();
        $sms_verification = GeneralSetting::first()->verification_phone;
        $mail_verification = GeneralSetting::first()->verification_email;
        $verification_content = NotificationTemplate::where('title', 'verification')->first();
        $msg_content = $verification_content->notification_content;
        $mail_content = $verification_content->mail_content;
        $sid = GeneralSetting::first()->twilio_acc_id;
        $token = GeneralSetting::first()->twilio_auth_token;
        $detail['otp'] = $otp;
        $detail['user_name'] = $user->name;
        $detail['app_name'] = GeneralSetting::first()->business_name;
        $data = ["{otp}", "{user_name}", "{app_name}"];
        $message1 = str_replace($data, $detail, $mail_content);
        if ($mail_verification == 1) {
            try {
                Mail::to($user['email_id'])->send(new Verification($message1));
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        if($sms_verification == 1)
        {
            try
            {
                $phone = $user->phone_code.$user->phone;
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
        return view('vendor.vendor.send_otp',compact('user'))->withStatus('Otp send into your mail .');
    }

    public function check_otp(Request $request)
    {
        $user = User::find($request->user_id);
        $otp = $request->digit_1 . $request->digit_2 . $request->digit_3 . $request->digit_4;
        if($user->otp == $otp)
        {
            $user->is_verified = 1;
            $user->save();
            if(Auth::loginUsingId($user->id))
            {
                return redirect('vendor/vendor_home');
            }
        }
        else
        {
            return redirect()->back()->withErrors('Otp does not match.');
        }
    }

    public function vendor_home()
    {
        abort_if(Gate::denies('vendor_dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $vendor = Vendor::where('user_id',auth()->user()->id)->first();
        $this->setLanguage($vendor);
        $user = [];
        $today_earnings = Settle::whereDate('created_at',Carbon::now())->where('vendor_id',$vendor->id)->sum('vendor_earning');
        $total_earnings = Settle::where('vendor_id',$vendor->id)->sum('vendor_earning');
        (new CustomController)->cancel_max_order();
        (new CustomController)->driver_cancel_max_order();
        $total_orders = Order::where('vendor_id',$vendor->id)->orderBy('id','DESC')->get();
        $orderIds = [];
        foreach($total_orders as $order)
        {
            array_push($orderIds,$order->id);
        }
        $topItems = OrderChild::whereIn('id',$orderIds)->groupBy('item')->select('item', DB::raw('count(*) as total'))->orderBy('total','DESC')->get()->each->setAppends(['itemName']);
        $total_users = User::whereIn('id',$user)->get();
        $recent_reviews = Review::where('vendor_id',$vendor->id)->whereBetween('created_at', [Carbon::now()->format('Y-m-d')." 00:00:00",  Carbon::now()->format('Y-m-d')." 23:59:59"])->get();
        $currency = GeneralSetting::first()->currency_symbol;
        $submenus = Submenu::where('vendor_id',$vendor->id)->get();
        $pending_today_orders = Order::where([['vendor_id',$vendor->id],['order_status','!=','COMPLETE']])->whereBetween('created_at', [Carbon::now()->format('Y-m-d')." 00:00:00",  Carbon::now()->format('Y-m-d')." 23:59:59"])->orderBy('id','DESC')->get();
        $today_orders = Order::whereDate('created_at',Carbon::now())->where('vendor_id',$vendor->id)->orderBy('id','DESC')->get();
        $delivery_persons = DeliveryPerson::where('vendor_id',$vendor->id)->orderBy('id','DESC')->get();
        return view('vendor.vendor_home',compact('today_orders','topItems','pending_today_orders','delivery_persons','submenus','total_users','recent_reviews','today_earnings','total_earnings','total_orders','currency'));
    }

    public function notification()
    {
        $vendor = Vendor::where('user_id',auth()->user()->id)->first();
        $notifications = Notification::where([['user_type','vendor'],['user_id',$vendor->id]])->get();
        return view('vendor.notification',compact('notifications'));
    }

    public function forgot_password()
    {
        return view('vendor.vendor_profile.vendor_forgot_password');
    }

    public function setLanguage($vendor)
    {
        $name = $vendor->vendor_language;
        if (!$name)
        {
            $name = 'english';
        }
        App::setLocale($name);
        session()->put('locale', $name);
        $direction = Language::where('name',$name)->first()->direction;
        session()->put('direction', $direction);
    }

    public function update_vendor()
    {
        $vendor = Vendor::where('user_id', auth()->user()->id)->first();
        $cuisines = Cuisine::where('status', 1)->get();
        $languages = Language::whereStatus(1)->get();
        $phone_codes = Country::get();
        $user = User::find($vendor->user_id);
        return view('vendor.vendor.edit_vendor', compact('vendor', 'cuisines','languages','phone_codes','user'));
    }

    public function print_setting()
    {
        $vendor = Vendor::where('user_id',auth()->user()->id)->first();
        return view('vendor.vendor.printer_setting',compact('vendor'));
    }

    public function update_printer_setting(Request $request)
    {
        $request->validate([
            'connector_type' => 'bail|required',
            'connector_descriptor' => 'bail|required',
            'connector_port' => 'bail|required_if:connector_type,network',
        ]);
        $vendor = Vendor::where('user_id', auth()->user()->id)->first();
        $vendor->update($request->all());
        return redirect('vendor/vendor_home')->with('msg','Printer Setting Update Successfully');
    }
}
