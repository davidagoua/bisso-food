<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPerson;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\Settle;
use App\Models\User;
use App\Models\Vendor;
use App\Models\WalletPayment;
use Bavix\Wallet\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function user_report(Request $request)
    {
        $users = User::whereHas('roles', function($q)
        {
            $q->where('title','user');
        });
        if($request->has('date_range'))
        {
            $date = explode(' - ',$request->date_range);
            $users = $users->whereBetween('created_at', [$date[0].' 00:00', $date[1].' 23:59'])->get();
        }
        else
        {
            $users = $users->get();
        }
        foreach ($users as $user)
        {
            $user['total_order'] = Order::where('user_id',$user->id)->get();
            $user['remain_amount'] = Order::where([['user_id',$user->id],['payment_status',0]])->sum('amount');
        }
        $active_user = $users->where('status',1)->count();
        $block_user = $users->where('status',0)->count();
        $currency = GeneralSetting::first()->currency_symbol;
        return view('admin.report.user_report',compact('users','currency','active_user','block_user'));
    }

    public function order_report(Request $request)
    {
        if($request->has('date_range'))
        {
            $date = explode(' - ',$request->date_range);
            $orders = Order::whereBetween('created_at', [$date[0].' 00:00', $date[1].' 23:59'])->get();
        }
        else
        {
            $orders = Order::orderBy('id','DESC')->get();
        }
        $currency = GeneralSetting::first()->currency_symbol;
        $month_earning = Settle::whereMonth('created_at', Carbon::now()->month)->sum('admin_earning');
        $year_earning = Settle::whereYear('created_at', date('Y'))->sum('admin_earning');
        return view('admin.report.order_report',compact('orders','year_earning','month_earning','currency'));
    }

    public function vendor_report(Request $request)
    {
        if ($request->has('date_range'))
        {
            $date = explode(' - ',$request->date_range);
            $vendors = Vendor::whereBetween('created_at', [$date[0].' 00:00', $date[1].' 23:59'])->get();
        }
        else
        {
            $vendors = Vendor::orderBy('id','DESC')->get();
        }
        foreach ($vendors as $vendor)
        {
            $vendor['total_order'] = Order::where('vendor_id',$vendor->id)->count();
            $vendor['vendor_earning'] = Settle::where('vendor_id',$vendor->id)->sum('vendor_earning');
            $vendor['remain_settle'] = Settle::where([['vendor_id',$vendor->id],['vendor_status',0]])->sum('vendor_earning');
            $vendor['compelte_settle'] = Settle::where([['vendor_id',$vendor->id],['vendor_status',1]])->sum('vendor_earning');
        }
        $currency = GeneralSetting::first()->currency_symbol;
        return view('admin.report.vendor_report',compact('vendors','currency'));
    }

    public function driver_report(Request $request)
    {
        if ($request->has('date_range'))
        {
            $date = explode(' - ',$request->date_range);
            $drivers = DeliveryPerson::whereBetween('created_at', [$date[0].' 00:00', $date[1].' 23:59'])->get();
        }
        else
        {
            $drivers = DeliveryPerson::orderBy('id','DESC')->get();
        }
        foreach ($drivers as $driver)
        {
            $driver['total_order'] = Order::where('delivery_person_id',$driver->id)->count();
            $driver['driver_earning'] = Settle::where('driver_id',$driver->id)->sum('driver_earning');
            $driver['remain_settle'] = Settle::where([['driver_id',$driver->id],['driver_status',0]])->sum('driver_earning');
            $driver['compelte_settle'] = Settle::where([['driver_id',$driver->id],['driver_status',1]])->sum('driver_earning');
        }
        $currency = GeneralSetting::first()->currency_symbol;
        $total_online_driver = DeliveryPerson::where('is_online',1)->count();
        return view('admin.report.driver_report',compact('drivers','total_online_driver','currency'));
    }

    public function earning_report()
    {
        $currency = GeneralSetting::first()->currency_symbol;
        return view('admin.report.earning_report',compact('drivers','currency'));
    }

    public function wallet_withdraw_report(Request $request)
    {
        $currency = GeneralSetting::first()->currency_symbol;
        $transactions = Transaction::with('wallet')->where('type','withdraw')->orderBy('id','DESC')->get();
        if ($request->has('date_range'))
        {
            $date = explode(' - ',$request->date_range);
            $transactions = Transaction::with('wallet')->where('type','withdraw')->whereBetween('created_at', [$date[0].' 00:00', $date[1].' 23:59'])->get();
        }
        $currency = GeneralSetting::first()->currency_symbol;
        foreach ($transactions as $transaction) {
            $transaction->payment_details = WalletPayment::where('transaction_id',$transaction->id)->first();
            $transaction->date = Carbon::parse($transaction->created_at);
            $transaction->user = User::find($transaction->payable_id);
            $transaction->order = Order::find($transaction->meta[0]);
        }
        return view('admin.report.wallet_withdraw_report',compact('currency','transactions'));
    }

    public function wallet_deposit_report(Request $request)
    {
        $currency = GeneralSetting::first()->currency_symbol;
        $currency = GeneralSetting::first()->currency_symbol;
        $transactions = Transaction::with('wallet')->where('type','deposit')->orderBy('id','DESC')->get();
        if ($request->has('date_range'))
        {
            $date = explode(' - ',$request->date_range);
            $transactions = Transaction::with('wallet')->where('type','deposit')->whereBetween('created_at', [$date[0].' 00:00', $date[1].' 23:59'])->get();
        }
        foreach ($transactions as $transaction) {
            $transaction->payment_details = WalletPayment::where('transaction_id',$transaction->id)->first();
            $transaction->date = Carbon::parse($transaction->created_at);
            $transaction->user = User::find($transaction->payable_id);
        }
        return view('admin.report.wallet_deposit_report',compact('currency','transactions'));
    }
}
