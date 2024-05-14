<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Mail\VendorMail;
use App\Models\Country;
use App\Models\DeliveryPerson;
use App\Models\DeliveryZone;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\PaymentSetting;
use App\Models\Settle;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gate;
use Hash;
use Illuminate\Support\Facades\Mail;
use Auth;
use DB;

class DeliveryPersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('delivery_person_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if(Auth::user()->load('roles')->roles->contains('title', 'admin'))
        {
            $delivery_persons = DeliveryPerson::orderBy('id','DESC')->get();
            foreach ($delivery_persons as $delivery_person)
            {
                if ($delivery_person->vendor_id != null)
                {
                    $tempVendor = Vendor::find($delivery_person->vendor_id,['name'])->makeHidden(['image','cuisine','vendor_logo','rate','review']);
                    $delivery_person->vendor = $tempVendor->name;
                }
                else
                {
                    $delivery_person->vendor = 'Super admin driver';
                }
            }
        }
        if(Auth::user()->load('roles')->roles->contains('title', 'vendor'))
        {
            $vendor = Vendor::where('user_id',auth()->user()->id)->first();
            $delivery_persons = DeliveryPerson::where('vendor_id',$vendor->id)->orderBy('id','DESC')->get();
        }
        return view('admin.delivery person.delivery_person',compact('delivery_persons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('delivery_person_add'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $delivery_zones = DeliveryZone::where('status',1)->get();
        $licenseSetting = GeneralSetting::find(1);
        $vehicals = json_decode($licenseSetting->driver_vehical_type);
        $phone_codes = Country::get();
        return view('admin.delivery person.create_delivery_person',compact('delivery_zones','vehicals','phone_codes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $licenseSetting = GeneralSetting::find(1);
        $vehicals = json_decode($licenseSetting->driver_vehical_type);
        if(count($vehicals))
        {
            foreach ($vehicals as $vehical)
            {
                if($vehical->vehical_type == $request->vehicle_type)
                {
                    if($vehical->license == 'yes')
                    {
                        $request->validate([
                            'national_identity' => 'required',
                            'licence_doc' => 'required',
                        ]);
                    }
                }
            }
        }
        $request->validate(
        [
            'first_name' => 'required',
            'last_name' => 'required',
            'delivery_zone_id' => 'required',
            'email_id' => 'bail|required|email|unique:delivery_person',
            'contact' => 'bail|required||numeric|digits_between:6,12',
            'full_address' => 'required',
            'vehicle_type' => 'required',
            'vehicle_number' => 'required',
            'licence_number' => 'required',
        ],
        [
            'email_id.required' => 'Email Address Field Is Required.',
        ]);
        $data = $request->all();
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

        if ($file = $request->hasfile('national_identity'))
        {
            $request->validate(
            ['national_identity' => 'max:1000'],
            [
                'national_identity.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            $data['national_identity'] = (new CustomController)->uploadImage($request->national_identity);
        }

        if ($file = $request->hasfile('licence_doc'))
        {
            $request->validate(
            ['licence_doc' => 'max:1000'],
            [
                'licence_doc.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            $data['licence_doc'] = (new CustomController)->uploadImage($request->licence_doc);
        }
        $data['status'] = $request->has('status') ? 1 : 0;
        $data['is_online'] = $request->has('is_online') ? 1 : 0;
        $data['is_verified'] = 1;
        $data['phone_code'] = '+91';
        $password = mt_rand(100000, 999999);
        $data['password'] = Hash::make($password);
        if(Auth::user()->load('roles')->roles->contains('title', 'vendor'))
        {
            $vendor = Vendor::where('user_id',auth()->user()->id)->first();
            $data['vendor_id'] = $vendor->id;
        }
        $delivery_person = DeliveryPerson::create($data);
        $message1 = 'Dear Delivery person your password is : '.$password;
        try
        {
            Mail::to($delivery_person->email_id)->send(new VendorMail($message1));
        }
        catch (\Throwable $th)
        {}
        if(Auth::user()->load('roles')->roles->contains('title', 'admin'))
        {
            return redirect('admin/delivery_person')->with('msg','Delivery person created successfully..!!');
        }
        if(Auth::user()->load('roles')->roles->contains('title', 'vendor'))
        {
            return redirect('vendor/deliveryPerson')->with('msg','Delivery person created successfully..!!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $delivery_person = DeliveryPerson::find($id);
        $orders = Order::where('delivery_person_id',$id)->get();
        $currency = GeneralSetting::first()->currency_symbol;
        return view('admin.delivery person.show_delivery_person',compact('orders','currency','delivery_person'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('delivery_person_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $delivery_zones = DeliveryZone::where('status',1)->get();
        $delivery_person = DeliveryPerson::find($id);
        $phone_codes = Country::get();
        return view('admin.delivery person.edit_delivery_person',compact('delivery_person','delivery_zones','phone_codes'));
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
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'delivery_zone_id' => 'required',
            'contact' => 'bail|required||numeric|digits_between:6,12',
            'full_address' => 'required',
            'vehicle_type' => 'required',
            'vehicle_number' => 'required',
            'licence_number' => 'required',
        ]);
        $data = $request->all();
        $id = DeliveryPerson::find($id);
        if ($file = $request->hasfile('image'))
        {
            $request->validate(
            ['image' => 'max:1000'],
            [
                'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            (new CustomController)->deleteImage(DB::table('delivery_person')->where('id', $id->id)->value('image'));
            $data['image'] = (new CustomController)->uploadImage($request->image);
        }

        if ($file = $request->hasfile('national_identity'))
        {
            $request->validate(
            ['national_identity' => 'max:1000'],
            [
                'national_identity.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            (new CustomController)->deleteImage(DB::table('delivery_person')->where('id', $id->id)->value('national_identity'));
            $data['national_identity'] = (new CustomController)->uploadImage($request->national_identity);
        }

        if ($file = $request->hasfile('licence_doc'))
        {
            $request->validate(
            ['licence_doc' => 'max:1000'],
            [
                'licence_doc.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            (new CustomController)->deleteImage(DB::table('delivery_person')->where('id', $id->id)->value('licence_doc'));
            $data['licence_doc'] = (new CustomController)->uploadImage($request->licence_doc);
        }
        $data['status'] = $request->has('status') ? 1 : 0;
        $data['is_online'] = $request->has('is_online') ? 1 : 0;
        $id->update($data);
        if(Auth::user()->load('roles')->roles->contains('title', 'admin'))
        {
            return redirect('admin/delivery_person')->with('msg','Delivery person update successfully..!!');
        }
        if(Auth::user()->load('roles')->roles->contains('title', 'vendor'))
        {
            return redirect('vendor/deliveryPerson')->with('msg','Delivery person update successfully..!!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeliveryPerson $delivery_person)
    {
        (new CustomController)->deleteImage(DB::table('delivery_person')->where('id', $delivery_person->id)->value('licence_doc'));
        (new CustomController)->deleteImage(DB::table('delivery_person')->where('id', $delivery_person->id)->value('national_identity'));
        (new CustomController)->deleteImage(DB::table('delivery_person')->where('id', $delivery_person->id)->value('image'));
        $delivery_person->delete();
        return response(['success' => true]);
    }

    public function driver_make_payment(Request $request)
    {
        $data = $request->all();
        $driver = DeliveryPerson::find($request->driver);
        $duration = explode(' - ',$data['duration']);
        $amount = Settle::where([['driver_id', $driver->id], ['driver_status', 0]])->where('created_at', '>=', $duration[0].' 00.00.00')->where('created_at', '<=', $duration[1].' 23.59.59')->sum('driver_earning');
        $paymentSetting = PaymentSetting::first();
        $duration = $data['duration'];
        $currency = GeneralSetting::first()->currency;
        return view('admin.delivery person.make_payment',compact('driver','currency','duration','amount','paymentSetting'));
    }

    public function finance_details($id)
    {
        $driver = DeliveryPerson::find($id);
        $now = Carbon::today();
        $orders = array();
        $currency = GeneralSetting::first()->currency_symbol;

        $past = Carbon::now()->subDays(35);
        $now = Carbon::today();
        $c = $now->diffInDays($past);
        $loop = $c / 10;
        $data = [];
        while ($now->greaterThan($past))
        {
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
            $settle = Settle::where([['driver_id', $driver->id],['driver_status',0],['created_at', '>=', $key['start'].' '.'00:00:00'],['created_at', '<=', $key['end'].' '.'23:59:59']])->get();
            $value['d_total_task'] = $settle->count();
            $value['admin_earning'] = $settle->sum('admin_earning');
            $value['driver_earning'] = $settle->sum('driver_earning');
            $value['vendor_earning'] = $settle->sum('vendor_earning');
            $value['d_total_amount'] = $value['admin_earning'] + $value['vendor_earning'] + $value['driver_earning'];
            $remainingOnline = Settle::where([['driver_id', $driver->id], ['payment', 0],['driver_status', 0],['created_at', '>=', $key['start'].' '.'00:00:00'],['created_at', '<=', $key['end'].' '.'23:59:59']])->get();
            $remainingOffline = Settle::where([['driver_id', $driver->id], ['payment', 1],['driver_status', 0],['created_at', '>=', $key['start'].' '.'00:00:00'],['created_at', '<=', $key['end'].' '.'23:59:59']])->get();

            $online = $remainingOnline->sum('driver_earning'); // admin e devana
            $offline = $remainingOffline->sum('admin_earning'); // admin e levana

            $value['duration'] = $key['start'] . ' - ' . $key['end'];
            $value['d_balance'] = $offline - $online; // + hoy to levana - devana
            array_push($settels,$value);
        }
        return view('admin.delivery person.driver_finance',compact('settels','currency','driver'));
    }

    public function change_status(Request $request)
    {
        $data = DeliveryPerson::find($request->id);
        $data->status = $data->status == 1 ? 0 : 1;
        $data->save();
        return response(['success' => true]);
    }

    public function driver_settle(Request $request)
    {
        $data = $request->all();
        $duration = explode(' - ',$data['duration']);
        $settles = Settle::where('driver_id',$data['driver'])->where('driver_status',0)->where('created_at', '>=', $duration[0].' 00.00.00')->where('created_at', '<=', $duration[1].' 23.59.00')->get();
        $driver = Settle::where('driver_status',0)->where('created_at', '>=', $duration[0].' 00.00.00')->where('created_at', '<=', $duration[1].' 23.59.00')->first(['driver_id']);
        foreach ($settles as $settle)
        {
            if(isset($data['driver_payment_token']))
            {
                $settle->driver_payment_token = $data['driver_payment_token'];
                $settle->driver_payment_type = $data['driver_payment_type'];
            }
            else
            {
                $settle->driver_payment_type = 'COD';
            }
            $settle->driver_status = 1;
            $settle->save();
        }
        return response(['success' => true , 'data' => $driver->driver_id]);
    }

    public function driver_fluterPayment(Request $request)
    {
        $temp = $request->all();
        $driver = DeliveryPerson::find($temp['driver']);
        $data['amount'] = $temp['amount'];
        $data['email'] = $driver->email_id;
        $data['phone'] = $driver->contact;
        $data['name'] = $driver->first_name;
        $data['duration'] = $temp['duration'];
        $data['driver'] = $driver->id;
        return view('admin.delivery person.flutterpayment',compact('data'));
    }

    public function driver_transction(Request $request,$duration,$driver)
    {
        $data = $request->all();
        $id = $request->input('transaction_id');
        $duration = explode(' - ',$duration);
        $settles = Settle::where('driver_id',$driver)->where('driver_status',0)->where('created_at', '>=', $duration[0].' 00.00.00')->where('created_at', '<=', $duration[1].' 23.59.00')->get();
        $driver = Settle::where('driver_id',$driver)->where('driver_status',0)->where('created_at', '>=', $duration[0].' 00.00.00')->where('created_at', '<=', $duration[1].' 23.59.00')->first(['driver_id']);
        foreach ($settles as $settle)
        {
            $settle->driver_payment_token = $id;
            $settle->driver_payment_type = 'FLUTTERWAVE';
            $settle->driver_status = 1;
            $settle->save();
        }
        return redirect('admin/delivery_person_finance_details/'.$driver->driver_id);
    }

    public function show_driver_settle_details($duration,$driver_id)
    {
        $duration = explode(' - ',$duration);
        $currency = GeneralSetting::first()->currency_symbol;
        $settle = Settle::where('driver_id',$driver_id)->where('created_at', '>=', $duration[0].' 00.00.00')->where('created_at', '<=', $duration[1].' 23.59.59')->get();
        foreach($settle as $s)
        {
            $s->date = $s->created_at->toDateString();
        }
        return response(['success' => true , 'data' => $settle , 'currency' => $currency]);
    }

    public function pending_amount($order_id)
    {
        Order::find($order_id)->update(['vendor_pending_amount' => 1]);
        return redirect()->back();
    }
}
