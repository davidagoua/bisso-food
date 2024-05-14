<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\GeneralSetting;
use App\Models\Vendor;
use App\Models\Settle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\DeliveryPerson;

class ReportController extends Controller
{
    public function user_report(Request $request)
    {
        $vendor = Vendor::where('user_id',auth()->user()->id)->first();
        $orders = Order::where('vendor_id',$vendor->id)->get();
        $user = [];
        foreach($orders as $order)
        {
            array_push($user,$order->user_id);
        }
        if ($request->has('date_range'))
        {
            $date = explode(' - ',$request->date_range);
            $users = User::whereIn('id',$user)->whereBetween('created_at', [$date[0].' 00:00', $date[1].' 23:59'])->get();
            $active_user = User::whereIn('id',$user)->whereBetween('created_at', [$date[0].' 00:00', $date[1].' 23:59'])->where('status',1)->count();
            $block_user = User::whereIn('id',$user)->whereBetween('created_at', [$date[0].' 00:00', $date[1].' 23:59'])->where('status',0)->count();

            foreach ($users as $user)
            {
                $user['total_order'] = Order::where('user_id',$user->id)->get();
                $user['remain_amount'] = Order::where([['user_id',$user->id],['payment_status',0]])->sum('amount');
            }
        }
        else
        {
            $users = User::whereIn('id',$user)->get();
            $active_user = User::whereIn('id',$user)->where('status',1)->count();
            $block_user = User::whereIn('id',$user)->where('status',0)->count();
            foreach ($users as $user)
            {
                $user['total_order'] = Order::where('user_id',$user->id)->get();
                $user['remain_amount'] = Order::where([['user_id',$user->id],['payment_status',0]])->sum('amount');
            }
        }
        $currency = GeneralSetting::first()->currency_symbol;
        return view('vendor.report.user_report',compact('users','currency','active_user','block_user'));
    }

    public function order_report(Request $request)
    {
        $vendor = Vendor::where('user_id',auth()->user()->id)->first();
        if ($request->has('date_range'))
        {
            $date = explode(' - ',$request->date_range);
            $orders = Order::whereBetween('created_at', [$date[0].' 00:00', $date[1].' 23:59'])->where('vendor_id',$vendor->id)->get();
        }
        else
        {
            $orders = Order::where('vendor_id',$vendor->id)->orderBy('id','DESC')->get();
        }
        foreach ($orders as $order) {
            if ($order->delivery_person_id) 
            {
                $delivery_person = DeliveryPerson::find($order->delivery_person_id,['id','first_name','last_name','image']);
                $order['deliver_person_name'] = $delivery_person->first_name.' '.$delivery_person->last_name;
            }
        }
        $currency = GeneralSetting::first()->currency_symbol;
        return view('vendor.report.order_report',compact('orders','currency'));
    }
}
