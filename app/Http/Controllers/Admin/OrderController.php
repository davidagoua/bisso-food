<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\Submenu;
use DateTime;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $vendor = Vendor::where('user_id',auth()->user()->id)->first();
        $orders = Order::orderBy('id','desc')->get();
        (new CustomController)->cancel_max_order();
        (new CustomController)->driver_cancel_max_order();
        return view('admin.order.order',compact('orders'));
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

    public function invoice($id)
    {
        $order = Order::find($id);
        $general_setting = GeneralSetting::first();
        return view('admin.order.invoice',compact('order','general_setting'));
    }

    public function invoice_print($id)
    {
        $order = Order::find($id);
        $general_setting = GeneralSetting::first();
        return view('admin.order.invoice_print',compact('order','general_setting'));
    }
}
