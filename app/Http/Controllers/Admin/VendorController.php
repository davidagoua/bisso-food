<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Mail\VendorMail;
use App\Models\Country;
use App\Models\Cuisine;
use App\Models\DeliveryZoneArea;
use App\Models\GeneralSetting;
use App\Models\Language;
use App\Models\Menu;
use App\Models\PaymentSetting;
use App\Models\PromoCode;
use App\Models\Review;
use App\Models\Role;
use App\Models\Settle;
use App\Models\Submenu;
use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;
use App\Models\WorkingHours;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use DB;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('admin_vendor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $vendors = Vendor::orderBy('id','DESC')->get();
        return view('admin.vendor.vendor',compact('vendors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('admin_vendor_add'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $cuisines = Cuisine::where('status',1)->get();
        $languages = Language::whereStatus(1)->get();
        $phone_codes = Country::get();
        return view('admin.vendor.create_vendor',compact('cuisines','languages','phone_codes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $data = $request->all();
        $request->validate([
            'name' => 'required',
            'email_id' => 'bail|required|email|unique:users',
            'contact' => 'bail|required|numeric|digits_between:6,12',
            'cuisine_id' => 'bail|required',
            'address' => 'required',
            'min_order_amount' => 'required',
            'for_two_person' => 'required',
            'avg_delivery_time' => 'required',
            'license_number' => 'required',
            'admin_comission_value' => 'required',
            'vendor_type' => 'required',
            'time_slot' => 'required',
        ]);

        $password = mt_rand(100000, 999999);
        $user =  User::create([
            'name' => $request->name,
            'email_id' => $request->email_id,
            'password' => Hash::make($password),
            'status' => 1,
            'is_verified' => 1,
            'image' => 'noimage.png',
            'phone' => $request->phone,
            'phone_code' => $request->phone_code,
        ]);
        $message1 = 'Dear Vendor your password is : '.$password;
        try
        {
            Mail::to($user->email_id)->send(new VendorMail($message1));
        }
        catch (\Throwable $th)
        {}

        $data['cuisine_id'] = implode(',',$request->cuisine_id);
        if ($file = $request->hasfile('image'))
        {
            $request->validate(
            ['image' => 'max:1000'],
            [
                'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            $data['image'] = (new CustomController)->uploadImage($request->image);
        }
        else
        {
            $data['image'] = 'product_default.jpg';
        }
        if ($file = $request->hasfile('vendor_logo'))
        {
            $request->validate(
            ['vendor_logo' => 'max:1000'],
            [
                'vendor_logo.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            $data['vendor_logo'] = (new CustomController)->uploadImage($request->vendor_logo);
        }
        else
        {
            $data['vendor_logo'] = GeneralSetting::first()->company_black_logo;
        }
        $data['status'] = $request->has('status') ? 1 : 0;
        $data['isTop'] = $request->has('isTop') ? 1 : 0;
        $data['isExplorer'] = $request->has('isExplorer') ? 1 : 0;
        $data['vendor_own_driver'] = $request->has('vendor_own_driver') ? 1 : 0;
        $data['user_id'] = $user->id;
        $vendor = Vendor::create($data);
        $role_id = Role::where('title','vendor')->orWhere('title','Vendor')->first();
        $user->roles()->sync($role_id);

        $start_time = strtolower(GeneralSetting::first()->start_time);
        $end_time = strtolower(GeneralSetting::first()->end_time);
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
        return redirect('admin/vendor')->with('msg','vendor addedd successfully..!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
        abort_if(Gate::denies('admin_vendor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $vendor['menu'] = Menu::where('vendor_id',$vendor->id)->get();
        return view('admin.vendor.show_vendor',compact('vendor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */

    public function edit(Vendor $vendor)
    {
        abort_if(Gate::denies('admin_vendor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $cuisines = Cuisine::where('status',1)->get();
        $languages = Language::whereStatus(1)->get();
        $phone_codes = Country::get();
        $user = User::find($vendor->user_id);
        return view('admin.vendor.edit_vendor',compact('vendor','cuisines','languages','phone_codes','user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'name' => 'required',
            'contact' => 'required|max:15',
            'cuisine_id' => 'bail|required',
            'address' => 'required',
            'min_order_amount' => 'required',
            'for_two_person' => 'required',
            'avg_delivery_time' => 'required',
            'license_number' => 'required',
            'admin_comission_value' => 'required',
            'vendor_type' => 'required',
            'time_slot' => 'required',
        ]);
        $data = $request->all();
        $data['cuisine_id'] = implode(',',$request->cuisine_id);
        $user = User::find($vendor->user_id);
        $user->phone_code = $request->phone_code;
        $user->phone = $request->phone;
        $user->save();
        if ($file = $request->hasfile('image'))
        {
            $request->validate(
            ['image' => 'max:1000'],
            [
                'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            (new CustomController)->deleteImage(DB::table('vendor')->where('id', $vendor->id)->value('image'));
            $data['image'] = (new CustomController)->uploadImage($request->image);
        }
        if ($file = $request->hasfile('vendor_logo'))
        {
            $request->validate(
            ['vendor_logo' => 'max:1000'],
            [
                'vendor_logo.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            (new CustomController)->deleteImage(DB::table('vendor')->where('id', $vendor->id)->value('vendor_logo'));
            $data['vendor_logo'] = (new CustomController)->uploadImage($request->vendor_logo);
        }
        $data['status'] = $request->has('status') ? 1 : 0;
        $data['isTop'] = $request->has('isTop') ? 1 : 0;
        $data['isExplorer'] = $request->has('isExplorer') ? 1 : 0;
        $data['vendor_own_driver'] = $request->has('vendor_own_driver') ? 1 : 0;
        $vendor->update($data);
        return redirect('admin/vendor')->with('msg','vendor updated successfully..!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        $promoCodes = PromoCode::all();
        foreach ($promoCodes as $promoCode)
        {
            $vIds = explode(',',$promoCode->vendor_id);
            if(count($vIds) > 0)
            {
                if (($key = array_search($vendor->id, $vIds)) !== false)
                {
                    unset($vIds[$key]);
                    $promoCode->vendor_id = implode(',',$vIds);
                }
                $promoCode->save();
            }
        }

        $delivery_zone_areas = DeliveryZoneArea::all();
        foreach ($delivery_zone_areas as $delivery_zone_area)
        {
            $vIds = explode(',',$delivery_zone_area->vendor_id);
            if(count($vIds) > 0)
            {
                if (($key = array_search($vendor->id, $vIds)) !== false)
                {
                    unset($vIds[$key]);
                    $delivery_zone_area->vendor_id = implode(',',$vIds);
                }
                $delivery_zone_area->save();
            }
        }

        $users = User::all();
        foreach ($users as $user)
        {
            $favs = explode(',',$user->faviroute);
            if(count($favs) > 0)
            {
                if (($key = array_search($vendor->id, $favs)) !== false)
                {
                    unset($favs[$key]);
                    $users->faviroute = implode(',',$favs);
                }
                $user->save();
            }
        }

        $vendorUsers = User::where('vendor_id',$vendor->id)->get();
        foreach ($vendorUsers as $vendorUser) {
            $vendorUser->vendor_id = null;
            $vendorUser->save();
        }

        foreach (Menu::where('vendor_id',$vendor->id)->get() as $menu) {
            (new CustomController)->deleteImage(DB::table('menu')->where('id', $menu->id)->value('image'));
        }

        foreach (Submenu::where('vendor_id',$vendor->id)->get() as $submenu) {
            (new CustomController)->deleteImage(DB::table('submenu')->where('id', $submenu->id)->value('image'));
        }

        User::find($vendor->user_id)->delete();
        return response(['success' => true]);
    }

    public function change_status(Request $request)
    {
        $data = Vendor::find($request->id);
        if($data->status == 0)
        {
            $data->status = 1;
            $data->save();
            return response(['success' => true]);
        }
        if($data->status == 1)
        {
            $data->status = 0;
            $data->save();
            return response(['success' => true]);
        }
    }

    public function edit_delivery_time($id)
    {
        $vendor = Vendor::find($id);
        $setting = GeneralSetting::first();
        $start_time = carbon::parse($setting["start_time"])->format('h:i a');
        $end_time = carbon::parse($setting["end_time"])->format('h:i a');
        $data = WorkingHours::where([['vendor_id',$id],['type','delivery_time']])->get();
        return view('admin.vendor.edit_delivery_time',compact('vendor','setting','start_time','end_time','data'));
    }

    public function update_delivery_time(Request $request)
    {
        $data = $request->all();
        $days = WorkingHours::where([['vendor_id',$data['vendor_id']],['type','delivery_time']])->get();
        for($i = 0; $i < count($days); $i++)
        {
            $master = array();
            $start_time = [$data['start_time_'.$days[$i]['day_index']]];
            $end_time = [$data['end_time_'.$days[$i]['day_index']]];
            for ($j = 0; $j < count($start_time[0]); $j++)
            {
                $temp['start_time'] = strtolower($start_time[0][$j]);
                $temp['end_time'] = strtolower($end_time[0][$j]);
                array_push($master,$temp);
            }
            $data['vendor_id'] = $request->vendor_id;
            $data['period_list'] = json_encode($master);
            $data['type'] = 'delivery_time';
            $data['day_index'] = $days[$i]['day_index'];
            if(isset($data['status'.$days[$i]['id']]))
            {
                $data['status'] = 1;
            }
            else
            {
                $data['status'] = 0;
            }
            $days[$i]->update($data);
        }
        return redirect()->back()->with('msg','timeslots changed successfully..!!');
    }

    public function edit_pick_up_time($id)
    {
        $vendor = Vendor::find($id);
        $setting = GeneralSetting::first();
        $start_time = carbon::parse($setting["start_time"])->format('h:i a');
        $end_time = carbon::parse($setting["end_time"])->format('h:i a');
        $data = WorkingHours::where([['vendor_id',$id],['type','pick_up_time']])->get();
        return view('admin.vendor.edit_pick_up_time',compact('vendor','setting','start_time','end_time','data'));
    }

    public function update_pick_up_time(Request $request)
    {
        $data = $request->all();
        $days = WorkingHours::where([['vendor_id',$data['vendor_id']],['type','pick_up_time']])->get();
        for($i = 0; $i < count($days); $i++)
        {
            $master = array();
            $start_time = [$data['start_time_'.$days[$i]['day_index']]];
            $end_time = [$data['end_time_'.$days[$i]['day_index']]];
            for ($j = 0; $j < count($start_time[0]); $j++)
            {
                $temp['start_time'] = strtolower($start_time[0][$j]);
                $temp['end_time'] = strtolower($end_time[0][$j]);
                array_push($master,$temp);
            }
            $data['vendor_id'] = $request->vendor_id;
            $data['period_list'] = json_encode($master);
            $data['type'] = 'pick_up_time';
            $data['day_index'] = $days[$i]['day_index'];
            if(isset($data['status'.$days[$i]['id']]))
            {
                $data['status'] = 1;
            }
            else
            {
                $data['status'] = 0;
            }
            $days[$i]->update($data);
        }
        return redirect()->back()->with('msg','timeslots changed successfully..!!');
    }

    public function edit_selling_timeslot($id)
    {
        $vendor = Vendor::find($id);
        $setting = GeneralSetting::first();
        $start_time = carbon::parse($setting["start_time"])->format('h:i a');
        $end_time = carbon::parse($setting["end_time"])->format('h:i a');
        $data = WorkingHours::where([['vendor_id',$id],['type','selling_timeslot']])->get();
        return view('admin.vendor.edit_selling_timeslot',compact('vendor','setting','start_time','end_time','data'));
    }

    public function update_selling_timeslot(Request $request)
    {
        $data = $request->all();
        $days = WorkingHours::where([['vendor_id',$data['vendor_id']],['type','selling_timeslot']])->get();
        for($i = 0; $i < count($days); $i++)
        {
            $master = array();
            $start_time = [$data['start_time_'.$days[$i]['day_index']]];
            $end_time = [$data['end_time_'.$days[$i]['day_index']]];
            for ($j = 0; $j < count($start_time[0]); $j++)
            {
                $temp['start_time'] = strtolower($start_time[0][$j]);
                $temp['end_time'] = strtolower($end_time[0][$j]);
                array_push($master,$temp);
            }
            $data['vendor_id'] = $request->vendor_id;
            $data['period_list'] = json_encode($master);
            $data['type'] = 'selling_timeslot';
            $data['day_index'] = $days[$i]['day_index'];
            if(isset($data['status'.$days[$i]['id']]))
            {
                $data['status'] = 1;
            }
            else
            {
                $data['status'] = 0;
            }
            $days[$i]->update($data);
        }
        return redirect()->back()->with('msg','timeslots changed successfully..!!');
    }

    public function vendor_change_password($id)
    {
        $vendor = Vendor::find($id);
        return view('admin.vendor.change_password',compact('vendor'));
    }

    public function vendor_update_password(Request $request,$id)
    {
        $request->validate([
            'old_password' => 'required|min:6',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password|min:6',
        ]);
        $data = $request->all();
        $id = auth()->user();

        if(Hash::check($data['old_password'], $id->password) == true)
        {
            $id->password = Hash::make($data['password']);
            $id->save();
            return redirect('admin/home')->with('message','Password Update Successfully...!!');
        }
        else
        {
            return redirect()->back()->with('message','Old password does not match');
        }
    }

    public function finance_details($id)
    {
        $vendor = Vendor::find($id);
        $now = Carbon::today();
        $orders = array();
        for ($i = 0; $i < 7; $i++)
        {
            $order = Order::where('vendor_id',$vendor->id)->whereDate('created_at', $now)->get();
            $discount = $order->sum('promocode_price');
            $vendor_discount = $order->sum('vendor_discount_price');
            $amount = $order->sum('amount');
            $order['amount'] = $discount + $vendor_discount + $amount;
            $order['admin_commission'] = $order->sum('admin_commission');
            $order['vendor_amount'] = $order->sum('vendor_amount');
            $now =  $now->subDay();
            $order['date'] = $now->toDateString();
            array_push($orders,$order);
        }

        $currency = GeneralSetting::first()->currency_symbol;

        $past = Carbon::now()->subDays(35);
        $now = Carbon::today();
        $c = $now->diffInDays($past);
        $loop = $c / 10;
        $data = [];
        while ($now->greaterThan($past)) {
            $t = $past->copy();
            $t->addDay();
            $temp['start'] = $t->toDateString();
            $past->addDays(10);
            if ($past->greaterThan($now)) {
                $temp['end'] = $now->toDateString();
            } else {
                $temp['end'] = $past->toDateString();
            }
            array_push($data, $temp);
        }

        $settels = array();
        $orderIds = array();
        foreach ($data as $key)
        {
            $settle = Settle::where('vendor_id', $vendor->id)->where('created_at', '>=', $key['start'].' 00.00.00')->where('created_at', '<=', $key['end'].' 23.59.59')->get();
            $value['d_total_task'] = $settle->count();
            $value['admin_earning'] = $settle->sum('admin_earning');
            $value['vendor_earning'] = $settle->sum('vendor_earning');
            $value['driver_earning'] = $settle->sum('driver_earning');
            $value['d_total_amount'] = $value['admin_earning'] + $value['vendor_earning'];
            $remainingOnline = Settle::where([['vendor_id', $vendor->id], ['payment', 0],['vendor_status', 0]])->where('created_at', '>=', $key['start'].' 00.00.00')->where('created_at', '<=', $key['end'].' 23.59.59')->get();
            $remainingOffline = Settle::where([['vendor_id', $vendor->id], ['payment', 1],['vendor_status', 0]])->where('created_at', '>=', $key['start'].' 00.00.00')->where('created_at', '<=', $key['end'].' 23.59.59')->get();

            $online = $remainingOnline->sum('vendor_earning'); // admin e devana
            $offline = $remainingOffline->sum('admin_earning'); // admin e levana

            $value['duration'] = $key['start'] . ' - ' . $key['end'];
            $value['d_balance'] = $offline - $online; // + hoy to levana - devana
            array_push($settels,$value);
        }
        return view('admin.vendor.finance_details',compact('vendor', 'orders', 'currency','settels'));
    }

    public function rattings($id)
    {
        $reviews = Review::where('vendor_id',$id)->get();
        $vendor = Vendor::find($id);
        return view('admin.vendor.ratting',compact('reviews','vendor'));
    }

    public function settle(Request $request)
    {
        $data = $request->all();
        $duration = explode(' - ',$data['duration']);
        $settles = Settle::where('vendor_id',$data['vendor'])->where('vendor_status',0)->where('created_at', '>=', $duration[0].' 00.00.00')->where('created_at', '<=', $duration[1].' 23.59.59')->get();
        $vendor = Settle::where('vendor_id',$data['vendor'])->where('vendor_status',0)->where('created_at', '>=', $duration[0].' 00.00.00')->where('created_at', '<=', $duration[1].' 23.59.59')->first(['vendor_id']);
        foreach ($settles as $settle)
        {
            // if(isset($data['payment_token']))
            // {
            //     $settle->payment_token = $data['payment_token'];
            //     $settle->payment_type = $data['payment_type'];
            // }
            // $settle->payment_type = 'COD';
            // $settle->vendor_status = 1;
            // $settle->save();
        }
        return response(['success' => true , 'data' => $vendor->vendor_id]);
    }

    public function make_payment(Request $request)
    {
        $data = $request->all();
        $vendor = Vendor::find($request->vendor);
        $duration = explode(' - ',$data['duration']);
        $amount = Settle::where([['vendor_id', $vendor->id], ['vendor_status', 0]])->where('created_at', '>=', $duration[0].' 00.00.00')->where('created_at', '<=', $duration[1].' 23.59.59')->sum('vendor_earning');
        $paymentSetting = PaymentSetting::first();
        $duration = $data['duration'];
        $currency = GeneralSetting::first()->currency;
        return view('admin.vendor.make_payment',compact('vendor','currency','duration','amount','paymentSetting'));
    }

    public function stripePayment(Request $request)
    {
        $data = $request->all();
        $currency = GeneralSetting::find(1)->currency;
        $paymentSetting = PaymentSetting::find(1);
        $stripe_sk = $paymentSetting->stripe_secret_key;
        $currency = GeneralSetting::find(1)->currency;
        $stripe = new \Stripe\StripeClient($stripe_sk);
        $charge = $stripe->charges->create([
            "amount" => intval($data['payment'] * 100),
            "currency" => $currency,
            "source" => $request->stripeToken,
        ]);
        return response(['success' => true , 'data' => $charge->id]);
    }

    public function fluterPayment(Request $request)
    {
        $temp = $request->all();
        $vendor = Vendor::find($temp['vendor']);
        $data['amount'] = $temp['amount'];
        $data['email'] = $vendor->email_id;
        $data['phone'] = $vendor->contact;
        $data['name'] = $vendor->name;
        $data['duration'] = $temp['duration'];
        $data['vendor'] = $vendor->id;
        return view('admin.vendor.flutterpayment',compact('data'));
    }

    public function transction(Request $request,$duration,$vendor)
    {
        $id = $request->input('transaction_id');
        $data = $request->all();
        $duration = explode(' - ',$duration);
        $settles = Settle::where('vendor_id',$vendor)->where('vendor_status',0)->where('created_at', '>=', $duration[0].' 00.00.00')->where('created_at', '<=', $duration[1].' 23.59.59')->get();
        $vendor = Settle::where('vendor_id',$vendor)->where('vendor_status',0)->where('created_at', '>=', $duration[0].' 00.00.00')->where('created_at', '<=', $duration[1].' 23.59.59')->first(['vendor_id']);
        if($request->input('status') != 'cancelled')
        {
            foreach ($settles as $settle)
            {
                $settle->payment_token = $id;
                $settle->payment_type = 'FLUTTERWAVE';
                $settle->vendor_status = 1;
                $settle->save();
            }
        }
        return redirect('admin/finance_details/'.$vendor->vendor_id);
    }

    public function show_settalement($duration)
    {
        $duration = explode(' - ',$duration);
        $currency = GeneralSetting::first()->currency_symbol;
        $settle = Settle::where('created_at', '>=', $duration[0].' 00.00.00')->where('created_at', '<=', $duration[1].' 23.59.59')->get();
        foreach($settle as $s)
        {
            $s->date = $s->created_at->toDateString();
        }
        return response(['success' => true , 'data' => $settle , 'currency' => $currency]);
    }

    public function vendor_login($id)
    {
        try {
            $vendor = Vendor::find($id);
            $user = User::where('id',$vendor->user_id)->first();
            Auth::login($user);
            return redirect('vendor/vendor_home');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
