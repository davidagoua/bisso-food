<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Models\GeneralSetting;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Review;
use App\Models\Role;
use App\Models\Settle;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorBankDetail;
use App\Models\WorkingHours;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        $vendor = Vendor::where('user_id', auth()->user()->id)->first();
        $vendor['menu'] = Menu::where('vendor_id', auth()->user()->id)->get();
        return view('vendor.vendor.show_vendor', compact('vendor'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $vendor = Vendor::where('user_id', auth()->user()->id)->first();
        // $cuisines = Cuisine::where('status', 1)->get();
        // $languages = Language::whereStatus(1)->get();
        // return view('vendor.vendor.edit_vendor', compact('vendor', 'cuisines','languages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $vendor = Vendor::find($id);
        $request->validate([
            'name' => 'required',
            'cuisine_id' => 'bail|required',
            'address' => 'required',
            'min_order_amount' => 'required',
            'for_two_person' => 'required',
            'avg_delivery_time' => 'required',
            'license_number' => 'required',
            'vendor_type' => 'required',
            'time_slot' => 'required',
            'contact' => 'required|max:15'
        ]);
        $data = $request->all();
        $data['cuisine_id'] = implode(',', $request->cuisine_id);
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
            (new CustomController)->deleteImage(DB::table('vendor')->where('id', $vendor['id'])->value('image'));
            $data['image'] = (new CustomController)->uploadImage($request->image);
        }
        if ($file = $request->hasfile('vendor_logo'))
        {
            $request->validate(
            ['vendor_logo' => 'max:1000'],
            [
                'vendor_logo.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            (new CustomController)->deleteImage(DB::table('vendor')->where('id', $vendor['id'])->value('vendor_logo'));
            $data['vendor_logo'] = (new CustomController)->uploadImage($request->vendor_logo);
        }
        if (isset($data['status'])) {
            $data['status'] = 1;
        } else {
            $data['status'] = 0;
        }
        $vendor->update($data);
        return redirect('vendor/vendor_home')->with('msg','Vendor Profile Update successfully..!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function delivery_timeslot()
    {
        $user_id = auth()->user()->id;
        $vendor = Vendor::where('user_id', $user_id)->first();
        $setting = GeneralSetting::first();
        $start_time = carbon::parse($setting["start_time"])->format('h:i a');
        $end_time = carbon::parse($setting["end_time"])->format('h:i a');
        $data = WorkingHours::where([['vendor_id', $vendor->id], ['type', 'delivery_time']])->get();
        return view('vendor.vendor.edit_delivery_time', compact('vendor', 'setting', 'start_time', 'end_time', 'data'));
    }

    public function pickup_timeslot()
    {
        $user_id = auth()->user()->id;
        $vendor = Vendor::where('user_id', $user_id)->first();
        $setting = GeneralSetting::first();
        $start_time = carbon::parse($setting["start_time"])->format('h:i a');
        $end_time = carbon::parse($setting["end_time"])->format('h:i a');
        $data = WorkingHours::where([['vendor_id', $vendor->id], ['type', 'pick_up_time']])->get();
        return view('vendor.vendor.edit_pick_up_time', compact('vendor', 'setting', 'start_time', 'end_time', 'data'));
    }

    public function selling_timeslot()
    {
        $user_id = auth()->user()->id;
        $vendor = Vendor::where('user_id', $user_id)->first();
        $setting = GeneralSetting::first();
        $start_time = carbon::parse($setting["start_time"])->format('h:i a');
        $end_time = carbon::parse($setting["end_time"])->format('h:i a');
        $data = WorkingHours::where([['vendor_id', $vendor->id], ['type', 'selling_timeslot']])->get();
        return view('vendor.vendor.edit_selling_timeslot', compact('vendor', 'setting', 'start_time', 'end_time', 'data'));
    }

    public function month_finanace()
    {
        $vendor = Vendor::where('user_id',auth()->user()->id)->first();
        $days = Carbon::now()->daysInMonth;
        $now = Carbon::now()->endOfMonth();
        $orders = array();
        for ($i = 0; $i < $days; $i++)
        {
            $order = Order::where([['vendor_id',$vendor->id],['order_status','COMPLETE']])->whereDate('created_at', $now)->get();
            $discount = $order->sum('promocode_price');
            $vendor_discount = $order->sum('vendor_discount_price');
            $amount = $order->sum('amount');
            $order['amount'] = $discount + $vendor_discount + $amount;
            $order['admin_commission'] = $order->sum('admin_commission');
            $order['vendor_amount'] = $order->sum('vendor_amount');
            $order['date'] = $now->toDateString();
            array_push($orders, $order);
            $now =  $now->subDay();
        }
        $now = Carbon::today();
        $month = $now->month;
        $currency = GeneralSetting::first()->currency_symbol;
        return view('vendor.vendor.month_finance', compact('month', 'orders', 'currency'));
    }

    public function month(Request $request)
    {
        $data = $request->all();
        $days = Carbon::parse($data['year'].'-'.$data['month'].'-01')->daysInMonth;
        $now = Carbon::parse($data['year'].'-'.$data['month'].'-01')->endOfMonth();
        $orders = array();
        for ($i = 0; $i < $days; $i++)
        {
            $order = Order::whereDate('created_at',$now)->get();
            $discount = $order->sum('promocode_price');
            $vendor_discount = $order->sum('vendor_discount_price');
            $amount = $order->sum('amount');
            $order['amount'] = $discount + $vendor_discount + $amount;
            $order['admin_commission'] = $order->sum('admin_commission');
            $order['vendor_amount'] = $order->sum('vendor_amount');
            $order['date'] = $now->toDateString();
            array_push($orders, $order);
            $now =  $now->subDay();
        }
        $now = $now = Carbon::parse($data['year'].'-'.$data['month'].'-01');
        $month = $now->month;
        $currency = GeneralSetting::first()->currency_symbol;
        return view('vendor.vendor.month', compact('month', 'orders', 'currency'));
    }

    public function transaction($duration)
    {
        $duration = explode(' - ',$duration);
        $settles = Settle::where('created_at', '>=', $duration[0])->where('created_at', '<=', $duration[1])->get();
    }

    public function bank_details()
    {
        $vendor = Vendor::where('user_id',auth()->user()->id)->first();
        $bank_details = VendorBankDetail::where('vendor_id',$vendor->id)->first();
        if($bank_details)
        {
            return view('vendor.vendor_bank_detail.update_bank_detail',compact('vendor','bank_details'));
        }
        else
        {
            return view('vendor.vendor_bank_detail.create_bank_detail',compact('vendor'));
        }
    }

    public function add_vendor_bank_details(Request $request)
    {
        $request->validate(
            [
                'bank_name' => 'bail|required',
                'branch_name' => 'bail|required',
                'ifsc_code' => 'bail|required|regex:/^[A-Za-z]{4}\d{7}$/',
                'clabe' => 'bail|required|numeric|digits:18',
                'account_number' => 'bail|required'
            ],
            [
                'ifsc_code.required' => 'IFSC Code Field Is Required.',
                'ifsc_code.regex' => 'IFSC Code Invalid.',
            ],
            );
        $data = $request->all();
        $details = VendorBankDetail::create($data);
        return redirect()->back()->with('msg','vendor bank Detail updated successfully');
    }

    public function edit_vendor_bank_details(Request $request,$id)
    {
        $request->validate(
            [
                'bank_name' => 'bail|required',
                'branch_name' => 'bail|required',
                'ifsc_code' => 'bail|required|regex:/^[A-Za-z]{4}\d{7}$/',
                'clabe' => 'bail|required|numeric|digits:18',
                'account_number' => 'bail|required'
            ],
            [
                'ifsc_code.required' => 'IFSC Code Field Is Required.',
                'ifsc_code.regex' => 'IFSC Code Invalid.',
            ],
            );
        $data = VendorBankDetail::find($id);
        $data->update($request->all());
        return redirect()->back()->with('msg','vendor bank Detail updated successfully');
    }

    public function rattings()
    {
        $vendor = Vendor::where('user_id',auth()->user()->id)->first();
        $reviews = Review::where('vendor_id',$vendor->id)->get();
        return view('vendor.vendor.ratting',compact('reviews','vendor'));
    }

    public function add_user(Request $request)
    {
        $request->validate([
            'name' => 'bail|required',
            'email_id' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6',
            'phone' => 'bail|required|numeric|digits_between:6,12',
        ]);
        $data = $request->all();
        $data['status'] = 1;
        $data['is_verified'] = 1;
        $data['image'] = 'noimage.png';
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $role_id = Role::where('title','User')->orWhere('title','user')->first();
        $user->roles()->sync($role_id);
        return response(['success' => true , 'data' => $user]);
    }
}
