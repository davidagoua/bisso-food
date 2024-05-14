<?php

namespace App\Http\Controllers\Vendor;

use App\Mail\StatusChange;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Mail\DriverOrder;
use App\Models\GeneralSetting;
use App\Models\Menu;
use App\Models\NotificationTemplate;
use App\Models\Order;
use App\Models\OrderChild;
use App\Models\Notification;
use App\Models\OrderSetting;
use App\Models\PromoCode;
use App\Models\Settle;
use App\Models\DeliveryZoneArea;
use App\Models\Submenu;
use App\Models\SubmenuCusomizationType;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Vendor;
use App\Models\VendorDiscount;
use App\Models\DeliveryPerson;
use App\Models\Role;
use App\Models\Tax;
use Illuminate\Http\Request;
use Gate;
use Illuminate\Support\Facades\Mail;
use Session;
use Symfony\Component\HttpFoundation\Response;
use DB;
use Config;
use Carbon\Carbon;
use Hash;
use OneSignal;
use App\Http\Controllers\Vendor\receiptprinter\ReceiptPrinter as ReceiptPrinter;
use Exception;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        abort_if(Gate::denies('vendor_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        (new CustomController)->cancel_max_order();
        (new CustomController)->driver_cancel_max_order();
        $currency = GeneralSetting::first()->currency_symbol;
        $vendor = Vendor::where('user_id',auth()->user()->id)->first();
        // $delivery_persons = DeliveryPerson::where('vendor_id',$vendor->id)->orderBy('id','DESC')->get();
        if ($request->has('date_range'))
        {
            $date = explode(' - ',$request->date_range);
            $orders = Order::where('vendor_id',$vendor->id)->whereBetween('created_at', [$date[0], $date[1]])->orderBy('id','desc')->get();
            $pendingOrders = Order::where([['vendor_id',$vendor->id],['order_status','PENDING']])->whereBetween('created_at', [$date[0], $date[1]])->orderBy('id','desc')->get();
            $approveOrders = Order::where([['vendor_id',$vendor->id],['order_status','APPROVE']])->orWhere('order_status','ACCEPT')->whereBetween('created_at', [$date[0], $date[1]])->orderBy('id','desc')->get();
            $deliveredOrders = Order::where([['vendor_id',$vendor->id],['order_status','DELIVERED']])->whereBetween('created_at', [$date[0], $date[1]])->orderBy('id','desc')->get();
            $pickUpOrders = Order::where([['vendor_id',$vendor->id],['order_status','PICKUP']])->whereBetween('created_at', [$date[0], $date[1]])->orderBy('id','desc')->get();
            $cancelOrders = Order::where([['vendor_id',$vendor->id],['order_status','CANCEL']])->whereBetween('created_at', [$date[0], $date[1]])->orderBy('id','desc')->get();
            $completeOrders = Order::where([['vendor_id',$vendor->id],['order_status','COMPLETE']])->whereBetween('created_at', [$date[0], $date[1]])->orderBy('id','desc')->get();
        }
        else
        {
            $orders = Order::where('vendor_id',$vendor->id)->orderBy('id','desc')->get();
            $pendingOrders = Order::where([['vendor_id',$vendor->id],['order_status','PENDING']])->orderBy('id','desc')->get();
            $approveOrders = Order::where([['vendor_id',$vendor->id],['order_status','APPROVE']])->orWhere('order_status','ACCEPT')->orderBy('id','desc')->get();
            $deliveredOrders = Order::where([['vendor_id',$vendor->id],['order_status','DELIVERED']])->orderBy('id','desc')->get();
            $pickUpOrders = Order::where([['vendor_id',$vendor->id],['order_status','PICKUP']])->orderBy('id','desc')->get();
            $cancelOrders = Order::where([['vendor_id',$vendor->id],['order_status','CANCEL']])->orderBy('id','desc')->get();
            $completeOrders = Order::where([['vendor_id',$vendor->id],['order_status','COMPLETE']])->orderBy('id','desc')->get();
        }
        foreach ($orders as $order) {
            if ($order->delivery_person_id) 
            {
                $delivery_person = DeliveryPerson::find($order->delivery_person_id,['id','first_name','last_name','image']);
                $order['deliver_person_name'] = $delivery_person->first_name.' '.$delivery_person->last_name;
            }
        }

        $d = array();
        $delivery_persons = DeliveryPerson::where([['vendor_id',$vendor->id],['status',1],['is_online',1]])->get();
       
        foreach ($delivery_persons as $value) 
        {
            $order = Order::where('delivery_person_id',$value->id)->get();
            if(count($order) == 0)
                array_push($d,$value->id);
            else
            {
                $os = Order::where('delivery_person_id',$value->id)->where([['order_status','!=','CANCEL'],['order_status','!=','COMPLETE'],['order_status','!=','REJECT']])->get()->pluck('delivery_person_id')->toArray();
                if (!count($os) > 0) {
                    array_push($d,$value->id);
                }
            }
        }
        $delivery_persons = DeliveryPerson::where([['vendor_id',$vendor->id],['status',1],['is_online',1]])->whereIn('id',$d)->get(['first_name','last_name','id'])->makeHidden(['image','deliveryzone']);
        return view('vendor.order.order',compact('orders','delivery_persons','currency','cancelOrders','completeOrders','pickUpOrders','pendingOrders','deliveredOrders','approveOrders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $total_item = 0;
        $grand_total = 0;
        if (Session::get('cart') != null)
        {
            $carts = Session::get('cart');
            foreach ($carts as $key => $cart)
            {
                $grand_total += intval($cart['price']);
                if (isset($cart['custimization']))
                {
                    foreach (json_decode($cart['custimization']) as $cust)
                    {
                        $grand_total += intval($cust->data->price);
                    }
                }
                $total_item++;
            }
        }
        $promoCodes = PromoCode::whereStatus(1)->get();
        $vendor = Vendor::where('user_id',auth()->user()->id)->first();
        $menus = Menu::where('vendor_id',$vendor->id)->orderBy('id','DESC')->get();
        $currency = GeneralSetting::first()->currency_symbol;
        $sub_menus = Submenu::whereStatus(1)->where('vendor_id',$vendor->id)->get();
        $tax = GeneralSetting::first()->isItemTax;
        foreach ($sub_menus as $submenu)
        {
            if ($tax == 0)
            {
                $price_tax = GeneralSetting::first()->item_tax;
                $disc = $submenu->price * $price_tax;
                $discount = $disc / 100;
                //$submenu->price = strval($submenu->price + $discount);
                $submenu->price = number_format($submenu->price + $discount, 2);
            }
            else
            {
                //$submenu->price = strval($submenu->price);
                $submenu->price = number_format($submenu->price, 2);   
            }
        }
        // return $sub_menus;
        $temps = [];
        $orderUsers = Order::where('vendor_id',$vendor->id)->get();
        foreach ($orderUsers as $orderUser)
        {
            $temp = User::find($orderUser->user_id);
            array_push($temps,$temp->id);
        }
        $vendorUsers = User::where('vendor_id',$vendor->id)->get();
        foreach ($vendorUsers as $vendorUser)
        {
            array_push($temps,$vendorUser->id);
        }
        $users = User::whereIn('id',$temps)->get();
        return view('vendor.order.create_order',compact('menus','users','promoCodes','currency','sub_menus','total_item','grand_total'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $vendor = Vendor::where('user_id',auth()->user()->id)->first();
        $cartData = Session::get('cart');
        $items = [];
        $tt = 0;
        foreach (json_decode($request->tax) as $tax)
        {
            $tt = $tt + $tax->tax;
        }
        $data['tax'] = $request->tax;
        foreach($cartData as $cart)
        {
            array_push($items,$cart['id']);
        }
        $amount = $request->amount + $tt;
        //4
        if ($vendor->admin_comission_type == 'percentage')
        {
            $comm = $amount * $vendor->admin_comission_value;
            $data['admin_commission'] = $comm / 100;
            $data['vendor_amount'] = $amount - $data['admin_commission'];
        }
        if ($vendor->admin_comission_type == 'amount') {
            $data['vendor_amount'] = $amount - $vendor->admin_comission_value;
            $data['admin_commission'] = $amount - $data['vendor_amount'];
        }
        $data['date'] = Carbon::now()->toDateString();
        $data['time'] = Carbon::now(env('timezone'))->format('h:i a');
        $data['order_id'] = '#' . rand(100000, 999999);
        $data['amount'] = $amount;

        if (intval($request->promocode_id) != 0)
        {
            $promocode = PromoCode::find($request->promocode_id);
            $data['amount'] = intval($data['amount']) - intval(round($request->promocode_price));
            $data['promocode_price'] = $request->promocode_price;
            $data['promocode_id'] = intval($request->promocode_id);
            $promocode->count_max_user = $promocode->count_max_user + 1;
            $promocode->count_max_count = $promocode->count_max_count + 1;
            $promocode->count_max_order = $promocode->count_max_order + 1;
            $promocode->save();
        }
        $data['delivery_type'] = 'SHOP';
        $data['item'] = implode(',',$items);
        $data['payment_type'] = 'COD';
        $data['payment_status'] = 1;
        $data['order_status'] = 'APPROVE';
        $data['vendor_id'] = $vendor->id;
        $data['user_id'] = $request->user_id;
        $data['tax'] = $request->tax;
        $order = Order::create($data);
        foreach (Session::get('cart') as $cart)
        {
            $order_child = array();
            $order_child['order_id'] = $order->id;
            $order_child['item'] = $cart['id'];
            $submenu = Submenu::find($cart['id']);
            if ($submenu->qty_reset == 'daily') {
                $ava_item = $submenu->availabel_item + $cart['qty'];
                $submenu->update(['availabel_item' => $ava_item]);
            }
            $order_child['price'] = $cart['price'];
            $order_child['qty'] = $cart['qty'];
            if(isset($cart['custimization']))
            {
                $order_child['custimization'] = $cart['custimization'];
            }
            OrderChild::create($order_child);
        }
        session()->forget('cart');
        return response(['success' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);
        $currency = GeneralSetting::first()->currency_symbol;
        return response(['success' => true , 'data' => ['order' => $order , 'currency' => $currency]]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return response(['success' => true]);
    }

    public function change_status(Request $request)
    {
        $status = strtoupper($request->status);
        $order = Order::find($request->id);
        $vendor = Vendor::where('id',$order->vendor_id)->first();
        $user = User::find($order->user_id);
        if ($request->status == 'APPROVE' || $request->status == 'approve')
        {
            $start_time = Carbon::now(env('timezone'))->format('h:i a');
            $order->order_start_time = $start_time;
            $order->save();
        }
        $order->order_status = $request->status;
        $order->save();
        if ($order->order_status == 'COMPLETE' || $order->order_status == 'complete')
        {
            $order->order_end_time = Carbon::now(env('timezone'))->format('h:i a');
            $order->payment_status = 1; 
            $order->save();
            $settle = array();
            $settle['vendor_id'] = $order->vendor_id;
            $settle['order_id'] = $order->id;
            if ($order->payment_type == 'COD')
                $settle['payment'] = 0;
            else
                $settle['payment'] = 1;

            $settle['vendor_status'] = 0;
            $settle['admin_earning'] = $order->admin_commission;
            $settle['vendor_earning'] = $order->vendor_amount;
            Settle::create($settle);
        }
        if (Session::get('vendor_driver') == 0)
        {
            if ($order->delivery_type == 'HOME' && ($request->status == 'APPROVE' || $request->status == 'approve'))
            {
                $areas = DeliveryZoneArea::all();
                $ds = array();
                foreach ($areas as $value)
                {
                    $vendorss = explode(',', $value->vendor_id);
                    if (($key = array_search($vendor->id, $vendorss)) !== false)
                    {
                        $ts = DB::select(DB::raw('SELECT id,delivery_zone_id,( 3959 * acos( cos( radians(' . $vendor->lat . ') ) * cos( radians( lat ) ) * cos( radians( lang ) - radians(' . $vendor->lang . ') ) + sin( radians(' . $vendor->lat . ') ) * sin( radians(lat) ) ) ) AS distance FROM delivery_zone_area HAVING distance < ' . $value->radius . ' ORDER BY distance'));
                        foreach ($ts as $t)
                        {
                            array_push($ds, $t->delivery_zone_id);
                        }
                    }
                }
                $near_drivers = DeliveryPerson::whereIn('delivery_zone_id', $ds)->get();
                foreach ($near_drivers as $near_driver)
                {
                    $this->sendDriverNotification($near_driver,$order,$vendor);
                }
            }
        }

        if ($user->language == 'spanish')
        {
            $status_change = NotificationTemplate::where('title','change status')->first();
            $mail_content = $status_change->spanish_mail_content;
            $notification_content = $status_change->spanish_notification_content;
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

            if (GeneralSetting::find(1)->customer_mail == 1)
            {
                try {
                    Mail::to($user->email_id)->send(new StatusChange($mail));
                } catch (\Throwable $th) {

                }
            }
        }
        else
        {
            $status_change = NotificationTemplate::where('title','change status')->first();
            $mail_content = $status_change->mail_content;
            $notification_content = $status_change->notification_content;
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

            if (GeneralSetting::find(1)->customer_mail == 1)
            {
                try {
                    Mail::to($user->email_id)->send(new StatusChange($mail));
                } catch (\Throwable $th) {

                }
            }
        }
        $notification = array();
        $notification['user_id'] = $user->id;
        $notification['user_type'] = 'user';
        $notification['title'] = $status;
        $notification['message'] = $message1;
        Notification::create($notification);
        return response(['success' => true, 'data' => ['status' => $status , 'order_id' => $order->id]]);
    }

    public function sendDriverNotification($near_driver,$order,$vendor)
    {
        $driver_notification = GeneralSetting::first()->driver_notification;
        $driver_mail = GeneralSetting::first()->driver_mail;
        $content = NotificationTemplate::where('title','delivery person order')->first();
        $detail['drive_name'] = $near_driver->first_name . ' - ' . $near_driver->last_name;
        $detail['vendor_name'] = $vendor->name;
        if (UserAddress::find($order->address_id))
        {
            $detail['address'] = UserAddress::find($order->address_id)->address;
        }
        $h = ["{driver_name}", "{vendor_name}", "{address}"];
        $notification_content = str_replace($h, $detail, $content->notification_content);
        if ($driver_notification == 1)
        {
            Config::set('onesignal.app_id', env('driver_app_id'));
            Config::set('onesignal.rest_api_key', env('driver_api_key'));
            Config::set('onesignal.user_auth_key', env('driver_auth_key'));
            try {
                OneSignal::sendNotificationToUser(
                    $notification_content,
                    $near_driver->device_token,
                    $url = null,
                    $data = null,
                    $buttons = null,
                    $schedule = null,
                    GeneralSetting::find(1)->business_name
                );
            }
            catch (\Throwable $th)
            {

            }
        }
        $p_notification = array();
        $p_notification['title'] = 'create order';
        $p_notification['user_type'] = 'driver';
        $p_notification['user_id'] = $near_driver->id;
        $p_notification['message'] = $notification_content;
        Notification::create($p_notification);
        if ($driver_mail == 1) {
            $mail_content = str_replace($h, $detail, $content->mail_content);
            try
            {
                Mail::to($near_driver->email_id)->send(new DriverOrder($mail_content));
            }
            catch (\Throwable $th) {
            }
        }
        return true;
    }

    public function driver_assign(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->delivery_person_id = $request->driver_id;
        $order->save();
        $driver = DeliveryPerson::find($request->driver_id);
        $vendor = Vendor::where('user_id',auth()->user()->id)->first();
        $driver_notification = GeneralSetting::first()->driver_notification;
        $driver_mail = GeneralSetting::first()->driver_mail;
        $content = NotificationTemplate::where('title', 'delivery person order')->first();
        $detail['drive_name'] = $driver->first_name . ' ' . $driver->last_name;
        $detail['vendor_name'] = $vendor->name;
        if (UserAddress::find($request->address_id))
        {
            $detail['address'] = UserAddress::find($request->address_id)->address;
        }
        $h = ["{driver_name}", "{vendor_name}", "{address}"];
        $notification_content = str_replace($h, $detail, $content->notification_content);
        if ($driver_notification == 1)
        {
            Config::set('onesignal.app_id', env('driver_app_id'));
            Config::set('onesignal.rest_api_key', env('driver_api_key'));
            Config::set('onesignal.user_auth_key', env('driver_auth_key'));
            try
            {
                OneSignal::sendNotificationToUser(
                    $notification_content,
                    $driver->device_token,
                    $url = null,
                    $data = null,
                    $buttons = null,
                    $schedule = null,
                    GeneralSetting::find(1)->business_name
                );
            } catch (\Throwable $th)
            {
            }
        }
        $p_notification = array();
        $p_notification['title'] = 'create order';
        $p_notification['user_type'] = 'driver';
        $p_notification['user_id'] = $driver->id;
        $p_notification['message'] = $notification_content;
        Notification::create($p_notification);

        if ($driver_mail == 1) {
            $mail_content = str_replace($h, $detail, $content->mail_content);
            try
            {
                Mail::to($driver->email_id)->send(new DriverOrder($mail_content));
            } catch (\Throwable $th) {
            }
        }
        return response(['success' => true]);
    }

    public function cart(Request $request)
    {
        $data = $request->all();
        $submenuItem = Submenu::where('id', $request->id)->first();
        $qty = 0;
        $price = 0;
        $grand_total = 0;
        $total_item = 0;
        if (Session::get('cart') == null)
        {
            if ($data['operation'] == "plus")
            {
                $master = array();
                $master['id'] = $request->id;
                $master['price'] = number_format($request->price,2);
                $master['qty'] = 1;
                Session::push('cart', $master);
                $price = number_format($request->price,2);
                $qty = 1;
            }
            foreach (Session::get('cart') as $key => $cart)
            {
                $grand_total += number_format($cart['price'],2);
                $total_item++;
                if (isset($cart['custimization']))
                {
                    foreach (json_decode($cart['custimization']) as $cust)
                    {
                        $grand_total += number_format($cust->data->price,2);
                    }
                }
            }
            return response(['success' => true, 'data' => ['if' => 'if','grand_total' => $grand_total , 'total_item' => $total_item , 'itemPrice' => $price ,'qty' => $qty]]);
        }
        else
        {
            $session = Session::get('cart');
            $tax = GeneralSetting::first()->isItemTax;
            if ($tax == 0)
            {
                $price_tax = GeneralSetting::first()->item_tax;
                $disc = $submenuItem->price * $price_tax;
                $discount = $disc / 100;
                $original_price = number_format($submenuItem->price + $discount,2);
            }
            else
            {
                $original_price = number_format($submenuItem->price,2);
            }
            if (in_array($request->id, array_column(Session::get('cart'), 'id')))
            {
                foreach ($session as $key => $value)
                {
                    if ($session[$key]['id'] == $request->id)
                    {
                        if ($data['operation'] == "plus")
                        {
                            $qty = $session[$key]['qty'] + 1;
                            $price = number_format($session[$key]['price'] +  $original_price,2);
                            $session[$key]['qty'] = $session[$key]['qty'] + 1;
                            $session[$key]['price'] = $session[$key]['price'] +  $original_price;
                        }
                        else
                        {
                            if (floatval($session[$key]['qty']) > 0)
                            {
                                $qty = $session[$key]['qty'] - 1;
                                $price = number_format($session[$key]['price'] - $original_price,2);
                                $session[$key]['qty'] = $session[$key]['qty'] - 1;
                                $session[$key]['price'] = $session[$key]['price'] - $original_price;
                            }
                            if($session[$key]['qty'] == 0)
                            {
                                unset($session[$key]);
                            }
                        }
                    }
                }
            }
            else
            {
                if ($data['operation'] == "plus")
                {
                    $master = array();
                    $master['id'] = $request->id;
                    $master['price'] = number_format($request->price,2);
                    $master['qty'] = 1;
                    $price = number_format($request->price,2);
                    $qty = 1;
                    array_push($session, $master);
                }
            }
            Session::put('cart', array_values($session));

            foreach (Session::get('cart') as $key => $cart)
            {
                $grand_total += $cart['price'];
                $total_item++;
                if (isset($cart['custimization']))
                {
                    foreach (json_decode($cart['custimization']) as $cust)
                    {
                        $grand_total += $cust->data->price;
                    }
                }
            }
            return response(['success' => true,'data' => ['grand_total' => number_format($grand_total,2) , 'total_item' => $total_item , 'itemPrice' => $price , 'qty' => $qty]]);
        }
    }

    public function custimization($submenu_id)
    {
        $vendor = Vendor::where('user_id',auth()->user()->id)->first();
        $custimizations = SubmenuCusomizationType::where([['submenu_id',$submenu_id],['vendor_id',$vendor->id]])->get();
        //dd($custimizations);
        $cust = '';
        $custimization_item = [];
        $session = Session::get('cart');
        foreach ($session as $key => $item)
        {
            $k = intval($item['id']);
            if ($k == intval($submenu_id))
            {
                if(isset($item['custimization']))
                {
                    $cust = $item['custimization'];
                }
            }
            foreach ($custimizations as $custimization) {
                if ($custimization->submenu_id == intval($submenu_id)) 
                {
                    if ($custimization->min_item_selection <= $item['qty'] && $custimization->max_item_selection >= $item['qty']) {
                        array_push($custimization_item,$custimization);
                    }
                }
            }
        }
        $currency = GeneralSetting::first()->currency_symbol;
        return response(['success' => true , 'data' => ['item' => $custimization_item , 'session' => $cust], 'currency' => $currency]);
    }

    public function add_user(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email_id' => 'required|email|unique:users',
            'phone' => 'required|digits_between:6,12'
        ]);
        $vendor = Vendor::where('user_id',auth()->user()->id)->first();
        $user = User::create([
            'name' => $request->name,
            'email_id' => $request->email_id,
            'phone' => $request->phone,
            'is_verified' => 1,
            'status' => 1,
            'password' => Hash::make(123456),
            'image' => 'noimage.png',
            'vendor_id' => $vendor->id,
        ]);
        $role_id = Role::where('title','user')->orWhere('title','User')->first();
        $user->roles()->sync($role_id);
        return response(['success' => true , 'data' => $user , 'from' => 'addUser']);
    }

    public function update_custimization(Request $request)
    {
        $data = $request->all();
        $custimize = array();
        $session = Session::get('cart');
        $custimization = SubmenuCusomizationType::where('submenu_id',$data['submenu_id'])->get();
        foreach ($session as $key => &$item)
        {
            $k = intval($session[$key]['id']);
            if ($k == $data['submenu_id'])
            {
                if(isset($data['custimization']))
                {
                    $item['custimization'] = [];
                    $item['custimization'] = json_encode($data['custimization']);
                }
                else
                {
                    unset($item['custimization']);
                }
            }
        }
        Session::put('cart', array_values($session));
        $totalamount = 0;
        foreach (Session::get('cart') as $key => $cart)
        {
            $totalamount += number_format($cart['price'],2);
            if (isset($cart['custimization']))
            {
                foreach (json_decode($cart['custimization']) as $cust)
                {
                    $totalamount += number_format($cust->data->price,2);
                }
            }
        }
        return response(['success' => true , 'data' => ['grand_total' => $totalamount]]);
    }

    public function displayBillWithCoupen(Request $request)
    {
        $tt = 0;
        foreach (json_decode($request->tax) as $tax)
        {
            $tt = $tt + $tax->tax;
        }
        $promoCode = PromoCode::find($request->promo_id);
        $currency = GeneralSetting::first()->currency_symbol;
        $users = explode(',', $promoCode->customer_id);
        if (($key = array_search($request->user_id, $users)) !== false)
        {
            $exploded_date = explode(' - ', $promoCode->start_end_date);
            $currentDate = date('Y-m-d', strtotime(Carbon::now()->toDateString()));
            if (($currentDate >= $exploded_date[0]) && ($currentDate <= $exploded_date[1]))
            {
                if ($promoCode->min_order_amount < $request->amount)
                {
                    if ($promoCode->coupen_type == 'both')
                    {
                        if ($promoCode->count_max_count < $promoCode->max_count && $promoCode->count_max_order < $promoCode->max_order && $promoCode->count_max_user < $promoCode->max_user)
                        {
                            $discount = [];
                            if ($promoCode->isFlat == 1)
                            {
                                $discount['discount'] = number_format($promoCode->flatDiscount,2);
                                $discount['totalAmount'] = number_format($request->amount,2);
                                $discount['tax'] = $request->tax;
                                $discount['finalTotal'] = number_format(($request->amount + $tt),2);
                                $discount['grandTotal'] = $discount['finalTotal'] - $discount['discount'];
                                $discount['procode_id'] = $promoCode->id;
                                return response(['success' => true , 'data' => $discount , 'currency' => $currency , 'promo' => $promoCode]);
                            }
                            else
                            {
                                if($promoCode->discountType == 'percentage')
                                {
                                    $disc = $promoCode->discount * $request->amount;
                                    $discount['discount'] = number_format(($disc/100),2);
                                    $discount['totalAmount'] = number_format(($request->amount),2);
                                    $discount['tax'] = $request->tax;
                                    $discount['finalTotal'] = number_format(($request->amount + $tt),2);
                                    $discount['grandTotal'] = $discount['finalTotal'] - $discount['discount'];
                                    $discount['procode_id'] = $promoCode->id;
                                    return response(['success' => true , 'data' => $discount ,'currency' => $currency , 'promo' => $promoCode]);
                                }
                                if($promoCode->discountType == 'amount')
                                {
                                    $discount['discount'] = number_format($promoCode->discount,2);
                                    $discount['totalAmount'] = number_format($request->amount,2);
                                    $discount['tax'] = $request->tax;
                                    $discount['finalTotal'] = number_format(($request->amount + $tt),2);
                                    $discount['grandTotal'] = $discount['finalTotal'] - $discount['discount'];
                                    $discount['procode_id'] = $promoCode->id;
                                    return response(['success' => true , 'data' => $discount ,'currency' => $currency , 'promo' => $promoCode]);
                                }
                                if($discount > $promoCode->max_disc_amount)
                                {
                                    $discount = $promoCode->max_disc_amount;
                                }
                            }
                            return response(['success' => true, 'data' => $discount->amount,'currency' => $currency ,'promo' => $promoCode]);
                        }
                        else
                        {
                            return response(['success' => false, 'data' => 'This coupen is expire..!!']);
                        }
                    }
                    else
                    {
                        if ($promoCode->coupen_type == 'pickup')
                        {
                            if ($promoCode->count_max_count < $promoCode->max_count && $promoCode->count_max_order < $promoCode->max_order && $promoCode->count_max_user < $promoCode->max_user)
                            {
                                $promo = PromoCode::where('id', $request->promo_id)->first(['id', 'image', 'isFlat', 'flatDiscount', 'discount', 'discountType']);
                                return response(['success' => true, 'data' => $promo]);
                            }
                            else
                            {
                                return response(['success' => false, 'data' => 'This coupen is expire..!!']);
                            }
                        }
                        else
                        {
                            return response(['success' => false, 'data' => 'This coupen is valid only for home delivery type']);
                        }
                    }
                }
                else {
                    return response(['success' => false, 'data' => 'This coupen not valid for less than ' . $currency . $promoCode->min_order_amount . ' amount']);
                }
            } else {
                return response(['success' => false, 'data' => 'Coupen is expire..!!']);
            }
        } else {
            return response(['success' => false, 'data' => 'Coupen is not valid for this user..!!']);
        }
        return response(['success' => true , 'data' => 'hello']);
    }

    public function change_submenu(Request $request)
    {
        $submenus = Submenu::where('menu_id',$request->menu_id)->get();
        $tax = GeneralSetting::first()->isItemTax;
        foreach ($submenus as $submenu)
        {
            if ($tax == 0)
            {
                $price_tax = GeneralSetting::first()->item_tax;
                $disc = $submenu->price * $price_tax;
                $discount = $disc / 100;
                //$submenu->price = strval($submenu->price + $discount);
                $submenu->price = number_format($submenu->price + $discount, 2);
            }
            else
            {
                //$submenu->price = strval($submenu->price);
                $submenu->price = number_format($submenu->price, 2);
            }
        }
        foreach ($submenus as $submenu)
        {
            $submenu->qty = 0;
            if (Session::get('cart') != null)
            {
                foreach (Session::get('cart') as $cart)
                {
                    if($cart['id'] == $submenu->id)
                    {
                        $submenu->qty = $cart['qty'];
                    }
                }
            }
        }
        $currency = GeneralSetting::first()->currency_symbol;
        return response(['success' => true , 'data' => $submenus , 'currency' => $currency]);
    }

    public function display_bill()
    {
        $totalamount = 0;
        $tax = 0;
        $finalTotal = 0;
        $vendor_tax = Vendor::where('user_id',auth()->user()->id)->first(['tax'])->makeHidden(['image','cuisine','vendor_logo','rate','review','vendor_name']);
        foreach (Session::get('cart') as $key => $cart)
        {
            $totalamount += number_format($cart['price'],2);
            if (isset($cart['custimization']))
            {
                foreach (json_decode($cart['custimization']) as $cust)
                {
                    $totalamount += number_format($cust->data->price,2);
                }
            }
        }
        $finalTotal = $totalamount;
        $taxs = Tax::whereStatus(1)->get();
        $t = [];
        foreach ($taxs as $tax)
        {
            $temp = array();
            if($tax->type == 'percentage')
            {
                $d = $totalamount * $tax->tax;
                $temp['tax'] = number_format($d / 100,2);
                $temp['name'] = $tax->name;
                $finalTotal += $temp['tax'];
            }
            if($tax->type == 'amount')
            {
                $temp['tax'] = number_format($tax->tax,2);
                $temp['name'] = $tax->name;
                $finalTotal += $temp['tax'];
            }
            array_push($t,$temp);
        }
        $currency = GeneralSetting::first()->currency_symbol;
        $h = array();
        $taxDisc = $totalamount * $vendor_tax->tax;
        $h['tax'] =$taxDisc / 100;
        $h['name'] = 'other tax';
        array_push($t,$h);
        $finalTotal = $finalTotal + $h['tax'];
        return response(['success' => true , 'currency' => $currency ,'data' =>['totalAmount' => number_format($totalamount,2),'finalTotal' => number_format($finalTotal,2) , 'admin_tax' => $t]]);
    }

    public function print_thermal($order_id)
    {
        $order = Order::find($order_id);
        $vendor = Vendor::find($order->vendor_id);
        $currency_code = GeneralSetting::first()->currency_code;
        $tax = 0;
        foreach (json_decode($order->tax) as $value) {
            $tax += $value->tax;
        }
        $store_name = $vendor->name;
        $store_address = $vendor->map_address;
        $store_phone = $vendor->contact;
        $store_email = $vendor->email_id;
        $non_taxed_total_amount = $order->amount - $tax;
        // Convert to percentage from total price & total taxed amount
        $tax_percentage =  ($tax/$non_taxed_total_amount) * 100;
        // return $non_taxed_total_amount;
        $transaction_id = $order->order_id;
        $currency = $currency_code;
       
        $items = [];
        foreach ($order->orderItems as $item) {
            $temp['name'] = $item['itemName'];
            $temp['qty'] = $item['qty'];
            if(isset($item['custimization']))
            {
                foreach ($item['custimization'] as $value) {
                    $temp['custimization'] = $value->data->name;
                }
            }
            else
            {
                $temp['custimization'] = "Doesn't Apply";
            }
            $temp['price'] = $item->price / $item['qty'];
            array_push($items,$temp);
        }
        // Init printer
        try {
            $printer = new ReceiptPrinter;
            try {
                $printer->init(
                    $vendor->connector_type,
                    $vendor->connector_descriptor
                );
            } catch (Exception $e) {
                return redirect()->back()->with('success',$e->getMessage());
            };

            // Set store info
            $printer->setStore($order_id, $store_name, $store_address, $store_phone, $store_email, $website_name = "website");

            // Set currency
            $printer->setCurrency($currency);

            // Add items
            foreach ($items as $item)
            {
                $printer->addItem(
                    $item['name'],
                    $item['qty'],
                    $item['price'] 
                );
            }
            // Set tax
            $printer->setTax($tax_percentage);

            // Calculate total
            $printer->calculateSubTotal();
            $printer->calculateGrandTotal();

            // Set transaction ID
            $printer->setTransactionID($transaction_id);

            // Set qr code
            $printer->setQRcode([
                'tid' => $transaction_id,
            ]);

            // Print receipt
            $printer->printReceipt();
        }
        catch (\Throwable $th) {
            throw $th;
        }
        return redirect()->back();
    }
}
