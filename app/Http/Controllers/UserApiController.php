<?php

namespace App\Http\Controllers;

use App\Mail\VendorOrder;
use App\Mail\Verification;
use App\Models\Banner;
use App\Models\Cuisine;
use App\Models\DeliveryPerson;
use App\Models\WalletPayment;
use App\Models\Faq;
use App\Models\Feedback;
use App\Models\GeneralSetting;
use App\Models\Menu;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\Order;
use App\Models\OrderChild;
use App\Models\OrderSetting;
use App\Models\PaymentSetting;
use App\Models\PromoCode;
use App\Models\Review;
use App\Models\Refund;
use App\Models\Role;
use App\Models\Submenu;
use App\Models\SubmenuCusomizationType;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Vendor;
use App\Models\VendorDiscount;
use App\Models\WorkingHours;
use App\Models\Tax;
use Carbon\Carbon;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mail;
use OneSignal;
use Twilio\Rest\Client;
use Bavix\Wallet\Models\Transaction;
use Arr;
use App\Mail\StatusChange;
use Throwable;

class UserApiController extends Controller
{
    public function apiUserLogin(Request $request)
    {
        $request->validate([
            'email_id' => 'bail|required_if:provider,GOOGLE,LOCAL|email',
            'password' => 'bail|required_if:provider,LOCAL|min:6',
            'provider_token' => 'bail|required_if:provider:GOOGLE,FACEBOOK',
            'provider' => 'bail|required',
        ]);
        $user = ([
            'email_id' => $request->email_id,
            'password' => $request->password,
        ]);

        if ($request->provider == 'LOCAL') {
            if (Auth::attempt($user)) {
                $user = Auth::user();
                if ($user->status == 1) {
                    if ($user->roles->contains('title', 'user')) {
                        if (isset($request->device_token)) {
                            $user->device_token = $request->device_token;
                            $user->save();
                        }
                        if ($user['is_verified'] == 1) {
                            $user['token'] = $user->createToken('mealUp')->accessToken;
                            return response()->json(['success' => true, 'data' => $user], 200);
                        } else {
                            $this->sendNotification($user);
                            $user['token'] = '';
                            return response(['success' => true, 'data' => $user, 'msg' => 'Otp send in your account']);
                        }
                    } else {
                        return response(['success' => false, 'msg' => 'only user can login...']);
                    }
                } else {
                    return response()->json(['success' => false, 'message' => 'you are block by admin please contact support'], 401);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'credintial does not match our record']);
            }
        } else {
            $data = $request->all();
            $data['role'] = 0;
            $data['is_verified'] = 1;
            $filtered = Arr::except($data, ['provider_token']);
            if ($data['provider'] !== 'LOCAL') {
                $data = User::firstOrCreate(['provider_token' => $request->provider_token], $filtered);
                if ($request->image != null) {
                    $url = $request->image;
                    $contents = file_get_contents($url);
                    $name = substr($url, strrpos($url, '/') + 1);
                    $destinationPath = public_path('/images/upload/') . $name . '.png';
                    file_put_contents($destinationPath, $contents);
                    $data['image'] = $name . '.png';
                } else {
                    $data['image'] = 'noimage.png';
                }
                if (isset($data['device_token'])) {
                    $data['device_token'] = $data->device_token;
                }
                $data->save();
                $token = $data->createToken('mealUp')->accessToken;
                $data['token'] = $token;
                $data->makeHidden('otp');
                return response()->json(['success' => true, 'data' => $data], 200);
            }
        }
    }

    public function apiUserRegister(Request $request)
    {
        $request->validate([
            'name' => 'bail|required',
            'email_id' => 'bail|required|unique:users',
            'password' => 'bail|min:6',
            'phone' => 'bail|required|numeric|digits_between:6,12',
            'phone_code' => 'required'
        ]);
        $admin_verify_user = GeneralSetting::find(1)->verification;
        $veri = $admin_verify_user == 1 ? 0 : 1;

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $data['status'] = 1;
        $data['image'] = 'noimage.png';
        $data['is_verified'] = $veri;
        $data['phone_code'] = $request->phone_code;
        $data['language'] = $request->language;
        $user = User::create($data);
        $role_id = Role::where('title', 'user')->orWhere('title', 'User')->first();
        $user->roles()->sync($role_id);

        if ($user['is_verified'] == 1) {
            $user['token'] = $user->createToken('mealUp')->accessToken;
            return response()->json(['success' => true, 'data' => $user, 'msg' => 'account created successfully..!!'], 200);
        } else {
            $admin_verify_user = GeneralSetting::find(1)->verification;
            if ($admin_verify_user == 1) {
                $this->sendNotification($user);
                return response(['success' => true, 'data' => $user, 'msg' => 'your account created successfully please verify your account']);
            }
        }
    }

    public function apiForgotPassword(Request $request)
    {
        $request->validate([
            'password' => 'bail|required|min:6',
            'password_confirmation' => 'bail|required|min:6|same:password',
            'user_id' => 'bail|required',
        ]);
        $data = $request->all();
        $user = User::find($request['user_id']);
        if ($user) {
            $user->password = Hash::make($data['password']);
            $user->save();
            return response(['success' => true, 'data' => 'Password Update Successfully...!!']);
        } else {
            return response(['success' => false, 'data' => 'User not found!!']);
        }
    }

    public function apiChangePassword(Request $request)
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
                return response(['success' => true , 'data' => 'Password Update Successfully.!']);
            }
            else
            {
                return response(['success' => false , 'data' => 'password and confirm password does not match.']);
            }
        }
        else
        {
            return response(['success' => false , 'data' => 'Old password does not match.']);
        }
    }

    public function apiSendOtp(Request $request)
    {
        $request->validate([
            'email_id' => 'bail|required',
            'where' => 'bail|required'
        ]);
        $user = User::where('email_id', $request->email_id)->first();
        if ($user)
        {
            if ($request->where == 'register')
            {
                $this->sendNotification($user);
            }
            if($request->where == 'forgot_password')
            {
                $this->ForgotPassword($user);
            }
            $user->makeHidden('otp');
            return response(['success' => true, 'data' => $user]);
        } else {
            return response(['success' => false, 'msg' => __('User Not Found.')]);
        }
    }

    public function apiCancelOrder(Request $request)
    {
        $request->validate([
            'cancel_reason' => 'required'
        ]);
        $data = $request->all();
        $order = Order::find($data['id']);
        if ($order) {
            $order->cancel_by = 'user';
            $order->order_status = 'CANCEL';
            $order->cancel_reason = $request->cancel_reason;
            $order->save();
            return response(['success' => true, 'data' => 'Cancel Order successfully..!!']);
        }
        return response(['success' => false, 'data' => 'No record found for this record']);
    }

    public function apiSingleMenu($menu_id)
    {
        $menu = Menu::find($menu_id);
        $tax = GeneralSetting::first()->isItemTax;
        $submenus = Submenu::where('menu_id', $menu->id)->get();
        foreach ($submenus as $submenu) {
            $submenu['custimization'] = SubmenuCusomizationType::where('submenu_id', $submenu->id)->get();
            if ($tax == 0) {
                $price_tax = GeneralSetting::first()->item_tax;
                $disc = $submenu->price * $price_tax;
                $tax = $disc / 100;
                $submenu->price = number_format($submenu->price + $tax,2);
            }
        }
        return response(['success' => true, 'data' => $submenus]);
    }

    public function apiSingleVendor(Request $request,$vendor_id)
    {
        $request->merge(['id' => $request->route('vendor_id')]);
        $request->validate(
            ['id' => ['required','numeric','exists:vendor,id']
        ]);

        $master = array();
        $master['vendor'] = Vendor::where([['id', $vendor_id], ['status', 1]])->first(['id', 'user_id','image', 'tax', 'name', 'map_address', 'for_two_person', 'vendor_type','lat','lang', 'cuisine_id'])->makeHidden(['vendor_logo']);
        if ($master['vendor']->tax == null) {
            $master['vendor']->tax = number_format(5.0,2);
        }

        $menus = Menu::where([['vendor_id', $vendor_id], ['status', 1]])->orderBy('id', 'DESC')->get(['id', 'name', 'image']);
        $tax = GeneralSetting::first()->isItemTax;
        foreach ($menus as $menu) {
            $menu['submenu'] = Submenu::where([['menu_id', $menu->id],['status',1]])->get(['id', 'qty_reset', 'item_reset_value','availabel_item','type', 'name', 'image', 'price']);
            foreach ($menu['submenu'] as $value)
            {
                if ($value->qty_reset == 'daily') {
                    $value->availabel_item = $value->availabel_item == null ? $value->item_reset_value : $value->availabel_item;
                }
                $value['custimization'] = SubmenuCusomizationType::where('submenu_id', $value->id)->get(['id', 'name', 'custimazation_item', 'type','min_item_selection','max_item_selection']);
                if ($tax == 0)
                {
                    $price_tax = GeneralSetting::first()->item_tax;
                    $disc = $value->price * $price_tax;
                    $discount = $disc / 100;
                    $value->price = number_format($value->price + $discount,2);
                }
                else
                {
                    $value->price = number_format($value->price,2);
                }
            }
        }
        $master['menu'] = $menus;
        $master['vendor_discount'] = VendorDiscount::where('vendor_id', $vendor_id)->orderBy('id', 'desc')->first(['id', 'type', 'discount', 'min_item_amount', 'max_discount_amount', 'start_end_date']);
        $master['delivery_timeslot'] = WorkingHours::where([['type', 'delivery_time'], ['vendor_id', $vendor_id]])->get(['id', 'day_index', 'period_list', 'status']);
        $master['pick_up_timeslot'] = WorkingHours::where([['type', 'pick_up_time'], ['vendor_id', $vendor_id]])->get(['id', 'day_index', 'period_list', 'status']);
        $master['selling_timeslot'] = WorkingHours::where([['type', 'selling_timeslot'], ['vendor_id', $vendor_id]])->get(['id', 'day_index', 'period_list', 'status']);
        $now = Carbon::now();
        $today = Carbon::createFromFormat('H:i', '21:00');
        $dayname = $now->format('l');

        foreach ($master['delivery_timeslot'] as $value) {
            $arr = json_decode($value['period_list'], true);
            if ($dayname == $value['day_index']) {
                foreach ($arr as $key => $a) {
                    $Hour1 = strtotime($a['start_time']);
                    $Hour2 = strtotime($a['end_time']);
                    $startofday = strtotime("01:00 am");

                    $seconds = $Hour2 - $Hour1;
                    $hours = $seconds / 60 / 60;
                    $hours = abs($hours);
                    $tts = date("H", $Hour1);
                    $seconds = $Hour2 - $Hour1;
                    $hours = $seconds / 60 / 60;
                    $beadded = 0;
                    if ($hours < 0) {
                        $remainDay = 24 - $tts;
                        $nextday = $Hour2 - $startofday;
                        $d = $nextday / 60 / 60;
                        // $d + 1;
                        $beadded = $remainDay + $d + 1;
                    } else {
                        $beadded = $hours;
                    }
                    $today = Carbon::createFromFormat('H:i', date("H:i", $Hour1));
                    $arr[$key]['new_start_time'] = $today->copy()->toDateTimeString();
                    $arr[$key]['new_end_time'] = $today->addHours($beadded)->toDateTimeString();
                }
            }
            $value['period_list'] = $arr;
        }
        foreach ($master['pick_up_timeslot'] as $pvalue) {
            $parr = json_decode($pvalue['period_list'], true);
            if ($dayname == $pvalue['day_index']) {
                foreach ($parr as $pkey => $pa) {
                    $pHour1 = strtotime($pa['start_time']);
                    $pHour2 = strtotime($pa['end_time']);
                    $pstartofday = strtotime("01:00 am");
                    $pseconds = $pHour2 - $pHour1;
                    $phours = $pseconds / 60 / 60;
                    $phours = abs($phours);
                    $ptts = date("H", $pHour1);
                    $pseconds = $pHour2 - $pHour1;
                    $phours = $pseconds / 60 / 60;
                    $pbeadded = 0;
                    if ($phours < 0) {
                        $premainDay = 24 - $ptts;

                        $pnextday = $pHour2 - $pstartofday;
                        $pd = $pnextday / 60 / 60;
                        $pbeadded = $premainDay + $pd + 1;
                    } else {
                        $pbeadded = $phours;
                    }
                    $ptoday = Carbon::createFromFormat('H:i', date("H:i", $pHour1));
                    $parr[$pkey]['new_start_time'] = $ptoday->copy()->toDateTimeString();
                    $parr[$pkey]['new_end_time'] = $ptoday->addHours($pbeadded)->toDateTimeString();
                }
            }
            $pvalue['period_list'] = $parr;
        }
        $reviewcheck = Review::where('vendor_id',$vendor_id)->exists();
        if($reviewcheck)
        {
            $review = Review::where('vendor_id',$vendor_id)->get();
            $def=[];
            foreach($review as $row)
            {
              $abc['id']=$row['id'];
              $abc['rate']=$row['rate'];
              $abc['comment']=$row['comment'];
              $abc['user']=$row->user;
              $abc['created_at']=$row['created_at'];
              array_push($def,$abc);
            }
           $master['review']=$def;
           return response(['success' => true, 'data' => $master]);
        }
        else
        {
            $master['review'] = [];
            return response(['success' => true, 'data' => $master]);
        }

    }

    public function apiPromoCode($vendor_id)
    {
        $promo = PromoCode::where('status', 1);
        $v = [];
        $promo_codes = PromoCode::where([['status', 1],['display_customer_app', 1]])->get();
        foreach ($promo_codes as $promo_code) {
            $vendorIds = explode(',', $promo_code->vendor_id);
            if (($key = array_search($vendor_id, $vendorIds)) !== false) {
                array_push($v, $promo_code->id);
            }
        }
        $promo = $promo->whereIn('id', $v)->get();
        return response(['success' => true, 'data' => $promo]);
    }

    public function apiTax()
    {
        $taxs = Tax::whereStatus(1)->get(['id', 'name', 'tax', 'type']);
        return response(['success' => true, 'data' => $taxs]);
    }

    public function apiApplyPromoCode(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'amount' => 'required',
            'delivery_type' => 'required',
            'promocode_id' => 'required',
        ]);
        $data = $request->all();

        $currency = GeneralSetting::first()->currency_symbol;
        $promoCode = PromoCode::find($data['promocode_id']);

        $users = explode(',', $promoCode->customer_id);
        if (($key = array_search(auth()->user()->id, $users)) !== false)
        {
            $exploded_date = explode(' - ', $promoCode->start_end_date);
            $currentDate = date('Y-m-d', strtotime($data['date']));
            if (($currentDate >= $exploded_date[0]) && ($currentDate <= $exploded_date[1])) {
                if ($promoCode->min_order_amount < $data['amount']) {
                    if ($promoCode->coupen_type == 'both') {
                        if ($promoCode->count_max_count < $promoCode->max_count && $promoCode->count_max_order < $promoCode->max_order && $promoCode->count_max_user < $promoCode->max_user) {
                            $promo = PromoCode::where('id', $data['promocode_id'])->first(['id', 'image', 'isFlat', 'flatDiscount', 'discount', 'discountType']);
                            return response(['success' => true, 'data' => $promo]);
                        } else {
                            return response(['success' => false, 'data' => 'Invalid Coupon!!']);
                        }
                    } else {
                        if ($promoCode->coupen_type == $data['delivery_type']) {
                            if ($promoCode->count_max_count < $promoCode->max_count && $promoCode->count_max_order < $promoCode->max_order && $promoCode->count_max_user < $promoCode->max_user) {
                                $promo = PromoCode::where('id', $data['promocode_id'])->first(['id', 'image', 'isFlat', 'flatDiscount', 'discount', 'discountType']);
                                return response(['success' => true, 'data' => $promo]);
                            } else {
                                return response(['success' => false, 'data' => 'Invalid Coupon!!']);
                            }
                        } else {
                            return response(['success' => false, 'data' => 'This coupen is not valid for ' . $data['delivery_type']]);
                        }
                    }
                } else {
                    return response(['success' => false, 'data' => 'This coupen not valid for less than ' . $currency . $promoCode->min_order_amount . ' amount']);
                }
            } else {
                return response(['success' => false, 'data' => 'The coupen has expired..!!']);
            }
        } else {
            return response(['success' => false, 'data' => 'Coupen is not valid for this user..!!']);
        }
    }

    public function apiCuisine()
    {
        $cuisines = Cuisine::where('status', 1)->orderBy('id', 'DESC')->get();
        return response(['success' => true, 'data' => $cuisines]);
    }

    public function apiFaq()
    {
        $faqs = Faq::where('type', 'customer')->orderBy('id', 'DESC')->get();
        return response(['success' => true, 'data' => $faqs]);
    }

    public function apiBanner()
    {
        $banners = Banner::where('status', 1)->orderBy('id', 'DESC')->get();
        return response(['success' => true, 'data' => $banners]);
    }

    public function apiSetting()
    {
        $setting = GeneralSetting::first(['about_us','android_customer_app_url','android_customer_version','business_availability','cancel_reason','currency','currency_symbol','customer_app_id','isPickup','privacy_policy','message','driver_auto_refrese','company_details','help','terms_and_condition'])->makeHidden(['blacklogo']);
        return response(['success' => true, 'data' => $setting]);
    }

    public function apiOrderSetting()
    {
        $setting = OrderSetting::first();
        return response(['success' => true, 'data' => $setting]);
    }

    public function apiPaymentSetting()
    {
        $setting = PaymentSetting::first();
        return response(['success' => true, 'data' => $setting]);
    }

    public function apiBookOrder(Request $request)
    {
        $request->validate([
            'date' => 'bail|required',
            'time' => 'bail|required|date_format:h:i a',
            'amount' => 'bail|required|numeric',
            'item' => 'bail|required',
            'vendor_id' => 'required',
            'delivery_type' => 'bail|required',
            'address_id' => 'bail|required_if:delivery_type,HOME',
            'payment_type' => 'bail|required',
            'payment_token' => 'bail|required_if:payment_type,STRIPE,RAZOR,PAYPAl',
            'tax' => 'required',
        ]);
        $bookData = $request->all();
        $vendor = Vendor::where('id', $bookData['vendor_id'])->first();
        $currency = GeneralSetting::find(1)->currency;
        $paymentSetting = PaymentSetting::find(1);
        if ($bookData['payment_type'] == 'STRIPE') {
            $stripe_sk = $paymentSetting->stripe_secret_key;
            $stripe = new \Stripe\StripeClient($stripe_sk);
            $charge = $stripe->charges->create(
                [
                    "amount" => $bookData['amount'] * 100,
                    "currency" => $currency,
                    "source" => $request->payment_token,
                ]
            );
            $bookData['payment_token'] = $charge->id;
        }
        if ($bookData['payment_type'] == 'WALLET')
        {
            $user = auth()->user();
            if ($bookData['amount'] > $user->balance) {
                return response(['success' => false, 'data' => "You have insufficient balance."]);
            }
        }
        if($bookData['payment_type'] == 'FLUTTERWAVE')
        {
            try {
                $secret_key = $paymentSetting['flutterwave_secret_key'];
                $curl = curl_init();

                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.flutterwave.com/v3/transactions/'.$request->payment_token.'/verify',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer '.$secret_key
                ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                $bookData['payment_token'] = json_decode($response)->data->flw_ref;
            } catch (\Throwable $th) { }
        }
        $bookData['user_id'] = auth()->user()->id;

        if (isset($bookData['promocode_id'])) {
            $promocode = PromoCode::find($bookData['promocode_id']);
            $promocode->count_max_user = $promocode->count_max_user + 1;
            $promocode->count_max_count = $promocode->count_max_count + 1;
            $promocode->count_max_order = $promocode->count_max_order + 1;
            $promocode->save();
        }

        $bookData['order_id'] = '#' . rand(100000, 999999);
        $bookData['vendor_id'] = $vendor->id;
        $order = Order::create($bookData);
        if ($bookData['payment_type'] == 'WALLET') {
            $user->withdraw($bookData['amount'], [$order->id]);
        }
        $bookData['item'] = json_decode($bookData['item'], true);
        foreach ($bookData['item'] as $child_item)
        {
            $order_child = array();
            $order_child['order_id'] = $order->id;
            $order_child['item'] = $child_item['id'];
            $order_child['price'] = $child_item['price'];
            $order_child['qty'] = $child_item['qty'];
            if (isset($child_item['custimization'])) {
                $order_child['custimization'] = $child_item['custimization'];
            }
            $submenu = Submenu::find($child_item['id']);
            if ($submenu->qty_reset == 'daily') {
                $submenu->availabel_item = $submenu->availabel_item + $child_item['qty'];
                $submenu->save();
            }
            OrderChild::create($order_child);
        }
        $this->sendVendorOrderNotification($vendor, $order->id);
        $this->sendUserNotification($bookData['user_id'], $order->id);
        $amount = $order->amount;
        $tax = array();
        if ($vendor->admin_comission_type == 'percentage') {
            $comm = $amount * $vendor->admin_comission_value;
            $tax['admin_commission'] = $comm / 100;
            $tax['vendor_amount'] = $amount - $tax['admin_commission'];
        }
        if ($vendor->admin_comission_type == 'amount') {
            $tax['vendor_amount'] = $amount - $vendor->admin_comission_value;
            $tax['admin_commission'] = $amount - $tax['vendor_amount'];
        }
        $order->update($tax);
        return response(['success' => true, 'data' => "order booked successfully wait for confirmation"]);
    }

    public function apiShowOrder()
    {
        (new CustomController)->cancel_max_order();
        (new CustomController)->driver_cancel_max_order();
        $orders = Order::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->get(['id','order_id', 'amount', 'vendor_id', 'order_status', 'delivery_person_id', 'delivery_charge', 'date', 'time', 'address_id']);
        foreach ($orders as $order) {
            if ($order->delivery_person_id != null) {
                $delivery_person = DeliveryPerson::find($order->delivery_person_id);
                $order->delivery_person = [
                    'name' => $delivery_person->first_name . ' ' . $delivery_person->last_name,
                    'image' => $delivery_person->image,
                    'contact' => $delivery_person->contact,
                ];
            }
            $order->review=Review::where('order_id',$order['id'])->where('user_id',auth()->user()->id)->exists();

        }
        return response(['success' => true, 'data' => $orders]);
    }

    public function apiVendor(Request $request)
    {
        $request->validate([
            'lat' => 'bail|required',
            'lang' => 'bail|required'
        ]);
        $radius = GeneralSetting::first()->radius;
        $vendors = Vendor::where('status', 1)->GetByDistance($request->lat, $request->lang, $radius)->orderBy('id', 'DESC')->get(['id', 'image', 'name', 'lat', 'lang', 'cuisine_id', 'vendor_type'])->makeHidden(['vendor_logo']);
        foreach ($vendors as $vendor) {
            $lat1 = $vendor->lat;
            $lon1 = $vendor->lang;
            $lat2 = $request->lat;
            $lon2 = $request->lang;
            $unit = 'K';
            if (($lat1 == $lat2) && ($lon1 == $lon2)) {
                $distance = 0;
            } else {
                $theta = $lon1 - $lon2;
                $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515;
                $unit = strtoupper($unit);
                if ($unit == "K") {
                    $distance = $miles * 1.609344;
                } else if ($unit == "N") {
                    $distance = $miles * 0.8684;
                } else {
                    $distance = $miles;
                }
            }
            $vendor['distance'] = round($distance);
            if (auth('api')->user() != null) {
                $user = auth('api')->user();
                $vendor['like'] = in_array($vendor->id, explode(',', $user->faviroute));
            } else {
                $vendor['like'] = false;
            }
        }
        return response(['success' => true, 'data' => $vendors]);
    }

    public function apiCheckOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'bail|required',
            'otp' => 'bail|required|min:4',
            'where' => 'bail|required',
        ]);
        $user = User::find($request->user_id);
        if ($user) {
            if ($user->otp == $request->otp) {
                if ($request->where == 'register') {
                    $user->is_verified = 1;
                    $user->save();
                    $user['token'] = $user->createToken('mealUp')->accessToken;
                    return response(['success' => true, 'data' => $user, 'msg' => 'SuccessFully verify your account...!!']);
                } else {
                    return response(['success' => true, 'data' => $user->id, 'msg' => 'SuccessFully verify your account...!!']);
                }
            } else {
                return response(['success' => false, 'msg' => 'Something went wrong otp does not match..!']);
            }
        } else {
            return response(['success' => false, 'msg' => 'Oops...user not found..!!']);
        }
    }

    public function apiUpdateUser(Request $request)
    {
        $data = $request->all();
        $request->validate([
            'name' => 'bail|required',
        ]);
        $id = auth()->user();
        $id->update($data);
        return response(['success' => true, 'data' => 'Update Successfully']);
    }

    public function apiUpdateImage(Request $request)
    {
        $request->validate([
            'image' => 'required',
        ]);
        $id = auth()->user();
        if (isset($request->image)) {
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
        return response(['success' => true, 'data' => 'image updated succssfully..!!']);
    }

    public function apiFaviroute(Request $request)
    {
        $data = auth()->user();
        if ($data != null) {
            if ($data->faviroute == null) {
                $data->faviroute = $request->id;
                $data->save();
                return response(['success' => true, 'data' => 'Add from faviroute successfully..!!']);
            } else {
                $like = explode(',', $data->faviroute);
                if (($key = array_search($request->id, $like)) !== false) {
                    unset($like[$key]);
                    $data->faviroute = implode(',', $like);
                    $data->save();
                    return response(['success' => true, 'data' => 'Remove from faviroute successfully..!!']);
                } else {
                    $restraunt = array();
                    $restraunt['like'] = $data->faviroute;
                    array_push($restraunt, $request->id);
                    $data->faviroute = implode(",", $restraunt);
                    $data->save();
                    return response(['success' => true, 'data' => 'Add to Favorite Successfully..!!']);
                }
            }
        } else {
            return response(['success' => false, 'data' => 'No Restaurant found..!!']);
        }
    }

    public function apiNearBy(Request $request)
    {
        $request->validate([
            'lat' => 'bail|required',
            'lang' => 'bail|required',
        ]);
        $radius = GeneralSetting::first()->radius;
        $vendors = Vendor::where('status', 1)->GetByDistance($request->lat, $request->lang, $radius)->get(['id', 'image', 'name', 'lat', 'lang', 'cuisine_id', 'vendor_type'])->makeHidden(['vendor_logo']);
        foreach ($vendors as $vendor) {
            if (auth('api')->user() != null) {
                $user = auth('api')->user();
                $vendor['like'] = in_array($vendor->id, explode(',', $user->faviroute));
            } else {
                $vendor['like'] = false;
            }
        }
        return response(['success' => true, 'data' => $vendors]);
    }

    public function apiRestFaviroute(Request $request)
    {
        $user = auth()->user();
        $faviroute = explode(',', $user->faviroute);
        $vendors = Vendor::where('status', 1)->whereIn('id', $faviroute)->get(['id', 'name', 'image', 'lat', 'lang', 'cuisine_id', 'vendor_type'])->makeHidden(['vendor_logo']);
        foreach ($vendors as $vendor) {
            $lat1 = $vendor->lat;
            $lon1 = $vendor->lang;
            $lat2 = $request->lat;
            $lon2 = $request->lang;
            $unit = 'K';
            if (($lat1 == $lat2) && ($lon1 == $lon2)) {
                $distance = 0;
            } else {
                $theta = $lon1 - $lon2;
                $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515;
                $unit = strtoupper($unit);
                if ($unit == "K") {
                    $distance = $miles * 1.609344;
                } else if ($unit == "N") {
                    $distance = $miles * 0.8684;
                } else {
                    $distance = $miles;
                }
            }
            $vendor['distance'] = round($distance);
        }
        return response(['success' => true, 'data' => $vendors]);
    }

    public function apiUserAddress()
    {
        $user_address = UserAddress::where('user_id', auth()->user()->id)->get();
        return response(['success' => true, 'data' => $user_address]);
    }

    public function apiAddAddress(Request $request)
    {
        $request->validate([
            'address' => 'required',
            'lat' => 'required',
            'lang' => 'required',
        ]);
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        UserAddress::create($data);
        return response(['success' => true, 'data' => 'user added successfully']);
    }

    public function apiEditAddress($address_id)
    {
        $user_address = UserAddress::find($address_id);
        return response(['success' => true, 'data' => $user_address]);
    }

    public function apiUpdateAddress(Request $request, $address_id)
    {
        $request->validate([
            'address' => 'required',
            'lat' => 'required',
            'lang' => 'required',
        ]);
        $user_address = UserAddress::find($address_id);
        $user_address->update($request->all());
        return response(['success' => true, 'data' => $user_address]);
    }

    public function apiRemoveAddress($address_id)
    {
        $id = UserAddress::find($address_id);
        $id->delete();
        return response(['success' => true, 'data' => 'remove successfully..!!']);
    }

    public function apiFilter(Request $request)
    {
        $result = Vendor::where('status', 1);
        $v = [];
        if (isset($request->cousins)) {
            $vendors = Vendor::where('status', 1)->get();
            foreach ($vendors as $vendor) {
                $cuisineId = explode(',', $vendor->cuisine_id);
                if (($key = array_search($request->cousins, $cuisineId)) !== false) {
                    array_push($v, $vendor->id);
                }
            }
            $result = $result->whereIn('id', $v);
        }

        if (isset($request->quick_filter)) {
            $result = $result->where('vendor_type', $request->quick_filter);
        }

        $data = $result->get(['id', 'name', 'image', 'lat', 'lang', 'cuisine_id', 'vendor_type'])->makeHidden(['vendor_logo']);

        if(isset($request->sorting))
        {
            if($request->sorting == 'high_to_low')
            {
                $data = $data->sortByDesc('rate')->values()->all();
            }
            if($request->sorting == 'low_to_high')
            {
                $data = $data->sortBy('rate')->values()->all();
            }
        }

        foreach ($data as $vendor) {
            $lat1 = $vendor->lat;
            $lon1 = $vendor->lang;
            $lat2 = $request->lat;
            $lon2 = $request->lang;
            $unit = 'K';
            if (($lat1 == $lat2) && ($lon1 == $lon2)) {
                $distance = 0;
            } else {
                $theta = $lon1 - $lon2;
                $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515;
                $unit = strtoupper($unit);
                if ($unit == "K") {
                    $distance = $miles * 1.609344;
                } else if ($unit == "N") {
                    $distance = $miles * 0.8684;
                } else {
                    $distance = $miles;
                }
            }
            $vendor['distance'] = round($distance);
            if (auth('api')->user() != null) {
                $user = auth('api')->user();
                $vendor['like'] = in_array($vendor->id, explode(',', $user->faviroute));
            } else {
                $vendor['like'] = false;
            }
        }
        return response(['success' => true, 'data' => $data]);
    }

    public function apiVegRest(Request $request)
    {
        $request->validate([
            'lat'=>'bail|required',
            'lang'=>'bail|required',
        ]);
        $radius = GeneralSetting::first()->radius;
        $vendors = Vendor::where([['vendor_type', 'veg'], ['status', 1]])->GetByDistance($request->lat, $request->lang, $radius)->orderBy('id', 'DESC')->get(['id', 'image', 'name', 'lat', 'lang', 'cuisine_id', 'vendor_type'])->makeHidden(['vendor_logo']);
        foreach ($vendors as $vendor) {
            $lat1 = $vendor->lat;
            $lon1 = $vendor->lang;
            $lat2 = $request->lat;
            $lon2 = $request->lang;
            $unit = 'K';
            if (($lat1 == $lat2) && ($lon1 == $lon2)) {
                $distance = 0;
            } else {
                $theta = $lon1 - $lon2;
                $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515;
                $unit = strtoupper($unit);
                if ($unit == "K") {
                    $distance = $miles * 1.609344;
                } else if ($unit == "N") {
                    $distance = $miles * 0.8684;
                } else {
                    $distance = $miles;
                }
            }
            $vendor['distance'] = round($distance);
            if (auth('api')->user() != null) {
                $user = auth('api')->user();
                $vendor['like'] = in_array($vendor->id, explode(',', $user->faviroute));
            } else {
                $vendor['like'] = false;
            }
        }
        return response(['success' => true, 'data' => $vendors]);
    }

    public function apiNonVegRest(Request $request)
    {
        $request->validate([
            'lat'=>'bail|required',
            'lang'=>'bail|required'
        ]);
        $radius = GeneralSetting::first()->radius;
        $vendors = Vendor::where(function ($query) {
            $query->where('vendor_type', 'non_veg')
            ->orWhere('vendor_type', 'all');
        })->GetByDistance($request->lat, $request->lang, $radius)->orderBy('id', 'DESC')->get(['id', 'image', 'name', 'lat', 'lang', 'cuisine_id', 'vendor_type'])->makeHidden(['vendor_logo']);
        foreach ($vendors as $vendor) {
            $lat1 = $vendor->lat;
            $lon1 = $vendor->lang;
            $lat2 = $request->lat;
            $lon2 = $request->lang;
            $unit = 'K';
            if (($lat1 == $lat2) && ($lon1 == $lon2)) {
                $distance = 0;
            } else {
                $theta = $lon1 - $lon2;
                $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515;
                $unit = strtoupper($unit);
                if ($unit == "K") {
                    $distance = $miles * 1.609344;
                } else if ($unit == "N") {
                    $distance = $miles * 0.8684;
                } else {
                    $distance = $miles;
                }
            }
            $vendor['distance'] = round($distance);
            if (auth('api')->user() != null) {
                $user = auth('api')->user();
                $vendor['like'] = in_array($vendor->id, explode(',', $user->faviroute));
            } else {
                $vendor['like'] = false;
            }
        }
        return response(['success' => true, 'data' => $vendors]);
    }

    public function apiTopRest(Request $request)
    {
        $request->validate([
            'lat'=>'bail|required',
            'lang'=>'bail|required'
        ]);
        $radius = GeneralSetting::first()->radius;
        $vendors = Vendor::where([['isTop', '1'], ['status', 1]])->GetByDistance($request->lat, $request->lang, $radius)->orderBy('id', 'DESC')->get(['id', 'image', 'name', 'lat', 'lang', 'vendor_type', 'cuisine_id'])->makeHidden(['vendor_logo']);
        foreach ($vendors as $vendor) {
            $lat1 = $vendor->lat;
            $lon1 = $vendor->lang;
            $lat2 = $request->lat;
            $lon2 = $request->lang;
            $unit = 'K';
            if (($lat1 == $lat2) && ($lon1 == $lon2)) {
                $distance = 0;
            } else {
                $theta = $lon1 - $lon2;
                $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515;
                $unit = strtoupper($unit);
                if ($unit == "K") {
                    $distance = $miles * 1.609344;
                } else if ($unit == "N") {
                    $distance = $miles * 0.8684;
                } else {
                    $distance = $miles;
                }
            }
            $vendor['distance'] = round($distance);
            if (auth('api')->user() != null) {
                $user = auth('api')->user();
                $vendor['like'] = in_array($vendor->id, explode(',', $user->faviroute));
            } else {
                $vendor['like'] = false;
            }
        }
        return response(['success' => true, 'data' => $vendors]);
    }

    public function apiExploreRest(Request $request)
    {
        $request->validate([
            'lat'=>'bail|required',
            'lang'=>'bail|required'
        ]);
        $radius = GeneralSetting::first()->radius;
        $vendors = Vendor::where([['isExplorer', '1'], ['status', 1]])->GetByDistance($request->lat, $request->lang, $radius)->orderBy('id', 'DESC')->get(['id', 'image', 'name', 'lat', 'lang', 'cuisine_id', 'vendor_type'])->makeHidden(['vendor_logo']);
        foreach ($vendors as $vendor) {
            $lat1 = $vendor->lat;
            $lon1 = $vendor->lang;
            $lat2 = $request->lat;
            $lon2 = $request->lang;
            $unit = 'K';
            if (($lat1 == $lat2) && ($lon1 == $lon2)) {
                $distance = 0;
            } else {
                $theta = $lon1 - $lon2;
                $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515;
                $unit = strtoupper($unit);
                if ($unit == "K") {
                    $distance = $miles * 1.609344;
                } else if ($unit == "N") {
                    $distance = $miles * 0.8684;
                } else {
                    $distance = $miles;
                }
            }
            $vendor['distance'] = round($distance);
            if (auth('api')->user() != null) {
                $user = auth('api')->user();
                $vendor['like'] = in_array($vendor->id, explode(',', $user->faviroute));
            } else {
                $vendor['like'] = false;
            }
        }
        return response(['success' => true, 'data' => $vendors]);
    }

    public function apiSingleOrder($id)
    {
        $order = Order::where('id', $id)->first(['id', 'order_id', 'vendor_id', 'amount', 'delivery_person_id', 'order_status', 'address_id', 'promocode_id', 'promocode_price', 'user_id', 'vendor_discount_price', 'delivery_charge']);
        $tax = 0;
        foreach (json_decode(Order::find($id)->tax) as $t) {
            $tax += $t->tax;
        }
        $order->tax = $tax;
        if ($order->delivery_person_id != null) {
            $order['delivery_person'] = DeliveryPerson::where('id', $order->delivery_person_id)->first(['first_name', 'last_name', 'image']);
        }
        return response(['success' => true, 'data' => $order]);
    }

    public function apiCuisineVendor(Request $request,$id)
    {
        $request->validate([
            'lat'=>'bail|required',
            'lang'=>'bail|required'
        ]);
        $radius = GeneralSetting::first()->radius;
        $v = Vendor::where('status', 1)->GetByDistance($request->lat, $request->lang, $radius)->get(['id', 'image', 'name', 'lat', 'lang', 'cuisine_id', 'vendor_type'])->makeHidden(['vendor_logo']);
        $vendors = array();
        foreach ($v as $vendor) {
            $cuisines = explode(',', $vendor->cuisine_id);
            if (($key = array_search($id, $cuisines)) !== false) {
                array_push($vendors, $vendor);
            }
        }
        return response(['success' => true, 'data' => $vendors]);
    }

    public function apiSearch(Request $request)
    {
        $data = $request->all();
        $req = [];
        $radius = GeneralSetting::first()->radius;
        $req['vendor'] = Vendor::where('status', 1)->GetByDistance($request->lat, $request->lang, $radius)->get(['id', 'image', 'name', 'lat', 'lang', 'cuisine_id', 'vendor_type'])->makeHidden(['vendor_logo']);
        // return $req['vendor'];
        $req['cuisine'] = Cuisine::where('name', 'LIKE', '%' . $data['name'] . "%")->get(['id', 'name', 'image']);
        return response(['success' => true, 'data' => $req]);
    }

    public function apiTracking($order_id)
    {
        $order = Order::find($order_id);
        $temp['user_lat'] = UserAddress::find($order->address_id)->lat;
        $temp['user_lang'] = UserAddress::find($order->address_id)->lang;
        $temp['vendor_lat'] = Vendor::find($order->vendor_id)->lat;
        $temp['vendor_lang'] = Vendor::find($order->vendor_id)->lang;
        $temp['driver_lat'] = DeliveryPerson::find($order->delivery_person_id)->lat;
        $temp['driver_lang'] = DeliveryPerson::find($order->delivery_person_id)->lang;
        $data = $temp;
        return response(['success' => true, 'data' => $data]);
    }

    public function apiAddReview(Request $request)
    {
        $request->validate([
            'rate' => 'required',
            'comment' => 'required',
            'order_id' => 'required',
        ]);
        $data = $request->all();
        if (Review::where([['order_id', $data['order_id'], ['user_id', auth()->user()->id]]])->exists() != true) {
            $data['user_id'] = auth()->user()->id;
            $data['contact'] = auth()->user()->phone;
            $data['vendor_id'] = Order::find($data['order_id'])->vendor_id;
            $d_image = [];
            if (isset($data['image'])) {
                if (count($data['image']) <= 3) {
                    foreach ($data['image'] as $image) {
                        $img = $image;
                        $img = str_replace('data:image/png;base64,', '', $img);
                        $img = str_replace(' ', '+', $img);
                        $data1 = base64_decode($img);
                        $Iname = uniqid();
                        $file = public_path('/images/upload/') . $Iname . ".png";
                        $success = file_put_contents($file, $data1);
                        array_push($d_image, $Iname . ".png");
                    }
                    $data['image'] = json_encode($d_image);
                } else {
                    return response(['success' => false, 'data' => 'only three image can upload']);
                }
            }
            $review = Review::create($data);
            return response(['success' => true, 'data' => "thanks for this review"]);
        } else {
            return response(['success' => false, 'data' => 'Review already addedd...!!']);
        }
    }

    public function apiAddFeedback(Request $request)
    {
        $request->validate([
            'rate' => 'required',
            'comment' => 'required',
        ]);
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $data['contact'] = auth()->user()->phone;
        $d_image = [];
        if (isset($data['image'])) {
            if (count($data['image']) <= 3) {
                foreach ($data['image'] as $image)
                {
                    $img = $image;
                    $img = str_replace('data:image/png;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $data1 = base64_decode($img);
                    $Iname = uniqid();
                    $file = public_path('/images/upload/') . $Iname . ".png";
                    $success = file_put_contents($file, $data1);
                    array_push($d_image, $Iname . ".png");
                }
                $data['image'] = json_encode($d_image);
            } else {
                return response(['success' => false, 'data' => 'only three image can upload']);
            }
        }
        Feedback::create($data);
        return response(['success' => true, 'data' => "thanks for your feedback"]);
    }

    public function FlutterWavepayment($order_id)
    {
        $order = Order::find($order_id);
        return view('flutterPaymentTest', compact('order'));
    }

    public function transction_verify(Request $request, $order_id)
    {
        $order = Order::find($order_id);
        $id = $request->input('transaction_id');
        if ($request->input('status') == 'successful') {
            $order->payment_token = $id;
            $order->payment_status = 1;
            $order->save();
            return view('transction_verify');
        } else {
            return view('cancel');
        }
    }

    public function apiUserOrderStatus()
    {
        $user = User::find(auth()->user()->id);
        $order = Order::where('user_id', $user->id)->where([['order_status', '!=', 'COMPLETE'], ['order_status', '!=', 'PENDING'], ['order_status', '!=', 'CANCEL']])->get(['order_status', 'id'])->makeHidden(['vendor', 'user', 'orderItems', 'user_address']);
        return response(['data' => $order, 'success' => true]);
    }

    public function apiRefund(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'refund_reason' => 'required',
        ]);
        $data = $request->all();
        if (Refund::where([['order_id', $data['order_id'], ['user_id', auth()->user()->id]]])->exists() != true) {
            $data['user_id'] = auth()->user()->id;
            $data['refund_status'] = 'PENDING';
            Refund::create($data);
            return response(['success' => true, 'data' => 'refund request generated successfully waiting for admin confirmation']);
        } else {
            return response(['success' => false, 'data' => 'refund request already generated..!!']);
        }
    }

    public function apiBankDetails(Request $request)
    {
        $request->validate([
            'account_number' => 'required',
            'micr_code' => 'required',
            'account_name' => 'required',
            'ifsc_code' => 'required',
        ]);
        $data = $request->all();
        $user = User::find(auth()->user()->id)->update($data);
        return response(['success' => true, 'data' => 'details update successfully..!!']);
    }

    public function sendNotification($user)
    {
        $admin_verify_user = GeneralSetting::find(1)->verification;
        $sms_verification = GeneralSetting::first()->verification_phone;
        $mail_verification = GeneralSetting::first()->verification_email;
        $verification_content = NotificationTemplate::where('title', 'verification')->first();
        if ($admin_verify_user == 1) {
            $otp = mt_rand(1000, 9999);
            if ($user->language == 'spanish') {
                $msg_content = $verification_content->spanish_notification_content;
                $mail_content = $verification_content->spanish_mail_content;

                $sid = GeneralSetting::first()->twilio_acc_id;
                $token = GeneralSetting::first()->twilio_auth_token;

                $detail['otp'] = $otp;
                $detail['user_name'] = $user->name;
                $detail['app_name'] = GeneralSetting::first()->business_name;
                $data = ["{otp}", "{user_name}", "{app_name}"];

                $user->otp = $otp;
                $user->save();
                if ($mail_verification == 1) {
                    $message1 = str_replace($data, $detail, $mail_content);
                    try {
                        Mail::to($user->email_id)->send(new Verification($message1));
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
                if ($sms_verification == 1) {
                    try {
                        $phone = $user->phone_code . $user->phone;
                        $message1 = str_replace($data, $detail, $msg_content);
                        $client = new Client($sid, $token);
                        $client->messages->create(
                            $phone,
                            array(
                                'from' => GeneralSetting::first()->twilio_phone_no,
                                'body' => $message1
                            )
                        );
                    } catch (\Throwable $th) {
                    }
                }
            } else {
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
                if ($mail_verification == 1) {
                    $message1 = str_replace($data, $detail, $mail_content);
                    try {
                        Mail::to($user->email_id)->send(new Verification($message1));
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
                if ($sms_verification == 1) {
                    try {
                        $phone = $user->phone_code . $user->phone;
                        $message1 = str_replace($data, $detail, $msg_content);
                        $client = new Client($sid, $token);
                        $client->messages->create(
                            $phone,
                            array(
                                'from' => GeneralSetting::first()->twilio_phone_no,
                                'body' => $message1
                            )
                        );
                    } catch (\Throwable $th) {
                    }
                }
            }
        }
    }

    public function sendVendorOrderNotification($vendor, $order_id)
    {
        $vendor_notification = GeneralSetting::first()->vendor_notification;
        $vendor_mail = GeneralSetting::first()->vendor_mail;
        $content = NotificationTemplate::where('title', 'vendor order')->first();
        $vendor_user = User::where('id', $vendor->user_id)->first();
        if ($vendor->vendor_language == 'spanish') {
            $detail['Vendor_name'] = $vendor->name;
            $detail['Order_id'] = $order_id;
            $detail['User_name'] = auth()->user()->name;
            $v = ["{Vendor_name}", "{Order_id}", "{User_name}"];
            $notification_content = str_replace($v, $detail, $content->spanish_notification_content);
            if ($vendor_notification == 1) {
                try {
                    Config::set('onesignal.app_id', env('vendor_app_id'));
                    Config::set('onesignal.rest_api_key', env('vendor_api_key'));
                    Config::set('onesignal.user_auth_key', env('vendor_auth_key'));
                    OneSignal::sendNotificationToUser(
                        $notification_content,
                        $vendor_user->device_token,
                        $url = null,
                        $data = null,
                        $buttons = null,
                        $schedule = null,
                        GeneralSetting::find(1)->business_name
                    );
                } catch (\Throwable $th) {
                    return $th->getMessage();
                }
            }
            $p_notification = array();
            $p_notification['title'] = 'create order';
            $p_notification['user_type'] = 'vendor';
            $p_notification['user_id'] = $vendor->id;
            $p_notification['message'] = $notification_content;
            Notification::create($p_notification);
            $mail = str_replace($v, $detail, $content->spanish_mail_content);
            if ($vendor_mail == 1) {
                try {
                    Mail::to($vendor->email_id)->send(new VendorOrder($mail));
                } catch (\Throwable $th) {
                }
            }
            return true;
        } else {
            $detail['Vendor_name'] = $vendor->name;
            $detail['Order_id'] = $order_id;
            $detail['User_name'] = auth()->user()->name;
            $v = ["{Vendor_name}", "{Order_id}", "{User_name}"];
            $notification_content = str_replace($v, $detail, $content->notification_content);
            if ($vendor_notification == 1) {
                try {
                    Config::set('onesignal.app_id', env('vendor_app_id'));
                    Config::set('onesignal.rest_api_key', env('vendor_api_key'));
                    Config::set('onesignal.user_auth_key', env('vendor_auth_key'));
                    OneSignal::sendNotificationToUser(
                        $notification_content,
                        $vendor_user->device_token,
                        $url = null,
                        $data = null,
                        $buttons = null,
                        $schedule = null,
                        GeneralSetting::find(1)->business_name
                    );
                } catch (\Throwable $th) {
                }
            }
            $p_notification = array();
            $p_notification['title'] = 'create order';
            $p_notification['user_type'] = 'vendor';
            $p_notification['user_id'] = $vendor->id;
            $p_notification['message'] = $notification_content;
            Notification::create($p_notification);
            $mail = str_replace($v, $detail, $content->mail_content);
            if ($vendor_mail == 1) {
                try {
                    Mail::to($vendor->email_id)->send(new VendorOrder($mail));
                } catch (\Throwable $th) {
                }
            }
            return true;
        }
    }

    public function sendUserNotification($user_id, $order_id)
    {
        $user = auth()->user();
        $order = Order::find($order_id);
        if ($user->language == 'spanish') {
            $status_change = NotificationTemplate::where('title', 'book order')->first();
            $mail_content = $status_change->spanish_mail_content;
            $notification_content = $status_change->spanish_notification_content;
            $detail['user_name'] = $user->name;
            $detail['order_id'] = $order->order_id;
            $detail['date'] = $order->date;
            $detail['order_status'] = $order->order_status;
            $detail['company_name'] = GeneralSetting::find(1)->business_name;
            $data = ["{user_name}", "{order_id}", "{date}", "{order_status}", "{company_name}"];

            $message1 = str_replace($data, $detail, $notification_content);
            $mail = str_replace($data, $detail, $mail_content);
            if (GeneralSetting::find(1)->customer_notification == 1) {
                if ($user->device_token != null) {
                    try {
                        Config::set('onesignal.app_id', env('customer_app_id'));
                        Config::set('onesignal.rest_api_key', env('customer_auth_key'));
                        Config::set('onesignal.user_auth_key', env('customer_api_key'));
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
            }
            $notification = array();
            $notification['user_id'] = $user->id;
            $notification['user_type'] = 'user';
            $notification['title'] = 'book order';
            $notification['message'] = $message1;
            Notification::create($notification);

            if (GeneralSetting::find(1)->customer_mail == 1) {
                try {
                    Mail::to($user->email_id)->send(new StatusChange($mail));
                } catch (\Throwable $th) {
                }
            }
        } else {
            $status_change = NotificationTemplate::where('title', 'book order')->first();
            $mail_content = $status_change->mail_content;
            $notification_content = $status_change->notification_content;
            $detail['user_name'] = $user->name;
            $detail['app_name'] = GeneralSetting::find(1)->business_name;
            $data = ["{user_name}", "{app_name}"];

            $message1 = str_replace($data, $detail, $notification_content);
            $mail = str_replace($data, $detail, $mail_content);
            if (GeneralSetting::find(1)->customer_notification == 1) {
                if ($user->device_token != null) {
                    try {
                        Config::set('onesignal.app_id', env('customer_app_id'));
                        Config::set('onesignal.rest_api_key', env('customer_auth_key'));
                        Config::set('onesignal.user_auth_key', env('customer_api_key'));
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
            }
            $notification = array();
            $notification['user_id'] = $user->id;
            $notification['user_type'] = 'user';
            $notification['title'] = 'book order';
            $notification['message'] = $message1;
            Notification::create($notification);

            if (GeneralSetting::find(1)->customer_mail == 1) {
                try {
                    Mail::to($user->email_id)->send(new StatusChange($mail));
                } catch (\Throwable $th) {
                }
            }
        }
        return true;
    }

    public function apiUserBalance()
    {
        $user = auth()->user();
        $transactions = Transaction::where('payable_id', $user->id)->orderBy('id', 'DESC')->get()->makeHidden(['updated_at', 'payable_type', 'wallet_id', 'confirmed', 'meta', 'uuid']);


        foreach ($transactions as $transaction) {
            $transaction->payment_details = WalletPayment::where('transaction_id', $transaction->id)->first();
            $transaction->date = Carbon::parse($transaction->created_at)->format('Y-m-d');
            $transaction->amount = abs($transaction->amount);

            if ($transaction->type == 'withdraw') {

                $transaction->order = Order::find($transaction->meta[0], ['id', 'vendor_id', 'order_id'])
                ->makeHidden(['user_address', 'orderItems', 'user', 'vendor']);
                $transaction->vendor_name = vendor::find($transaction->order->vendor_id)->name;
            }
        }
        $data['transaction'] = $transactions;
        $data['total_balance'] = auth()->user()->balance;
        return response(['success' => true, 'data' => $data]);
    }

    public function apiWalletBalance()
    {
        return response(['success' => true, 'data' => auth()->user()->balance]);
    }

    public function apiUserAddBalance(Request $request)
    {
        $request->validate([
            'amount' => 'bail|required|numeric',
            'payment_type' => 'bail|required',
            'payment_token' => 'bail|required',
        ]);
        $data = $request->all();
        $user = auth()->user();
        $deposit = $user->deposit($data['amount']);
        $transction = array();
        $transction['transaction_id'] = $deposit->id;
        $transction['payment_type'] = strtoupper($request->payment_type);
        $transction['payment_token'] = $request->payment_token;
        $transction['added_by'] = 'user';
        $paymentSetting = PaymentSetting::find(1);
        if ($transction['payment_type'] == 'STRIPE')
        {
            $stripe_sk = $paymentSetting->stripe_secret_key;
            $currency = GeneralSetting::find(1)->currency;
            $stripe = new \Stripe\StripeClient($stripe_sk);
            $charge = $stripe->charges->create(
                [
                    "amount" => $data['amount'],
                    "currency" => $currency,
                    "source" => $request->payment_token,
                ]
            );
            $transction['payment_token'] = $charge->id;
        }
        if ($transction['payment_type'] == 'FLUTTERWAVE')
        {
            try {
                $secret_key = $paymentSetting['flutterwave_secret_key'];
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.flutterwave.com/v3/transactions/'.$request->payment_token.'/verify',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer '.$secret_key),
                ));
                $response = curl_exec($curl);

                curl_close($curl);
                $transction['payment_token'] = json_decode($response)->data->flw_ref;

            } catch (\Throwable $th) { }
        }
        WalletPayment::create($transction);
        return response(['success' => true, 'data' => 'balance added']);
    }

    public function ForgotPassword($user)
    {
        $verification_content = NotificationTemplate::where('title','verification')->first();
        $otp = mt_rand(1000, 9999);
        $user->otp = $otp;
        $user->save();
        if ($user->language == 'spanish')
        {
            $msg_content = $verification_content->spanish_notification_content;
            $mail_content = $verification_content->spanish_mail_content;

            $sid = GeneralSetting::first()->twilio_acc_id;
            $token = GeneralSetting::first()->twilio_auth_token;
            $detail['otp'] = $otp;
            $detail['user_name'] = $user->name;
            $detail['app_name'] = GeneralSetting::first()->business_name;
            $data = ["{otp}", "{user_name}", "{app_name}"];

            $message1 = str_replace($data, $detail, $mail_content);
            try {
                Mail::to($user->email_id)->send(new Verification($message1));
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        else
        {
            $mail_content = $verification_content->mail_content;
            $detail['otp'] = $otp;
            $detail['user_name'] = $user->name;
            $detail['app_name'] = GeneralSetting::first()->business_name;
            $data = ["{otp}", "{user_name}","{app_name}"];
            $message1 = str_replace($data, $detail, $mail_content);
            try {
                Mail::to($user->email_id)->send(new Verification($message1));
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    }

    public function apiNotification()
    {
        $notifications = Notification::where('user_id',auth()->user()->id)->get();
        return response(['success' => true, 'data' => $notifications]);
    }
}
