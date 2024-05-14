<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\PaymentSetting;
use App\Models\Refund;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class RefaundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('refund_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $refunds = Refund::orderBy('id','DESC')->get();
        $currency = GeneralSetting::first()->currency_symbol;
        return view('admin.refund.refund',compact('refunds','currency'));
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
        $refund = Refund::find($id);
        $paymentSetting = PaymentSetting::first();
        $currency = GeneralSetting::find(1)->currency_symbol;
        $c = GeneralSetting::find(1)->currency;
        return view('admin.refund.show_refund',compact('refund','currency','c','paymentSetting'));
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
    public function destroy($id)
    {
        //
    }

    public function status(Request $request)
    {
        $data = $request->all();
        $refund = Refund::find($data['refund_id'])->update(['refund_status' => $data['refund_status']]);
        return response(['success' => true]);
    }

    public function refaundStripePayment(Request $request)
    {
        $refund = Refund::find($request->refund_id);
        $currency = GeneralSetting::find(1)->currency;
        $paymentSetting = PaymentSetting::find(1);
        $stripe_sk = $paymentSetting->stripe_secret_key;
        $currency = GeneralSetting::find(1)->currency;
        $stripe = new \Stripe\StripeClient($stripe_sk);
        $charge = $stripe->charges->create([
            "amount" => $refund->order['amount'] * 100,
            "currency" => $currency,
            "source" => $request->stripeToken,
        ]);
        return response(['success' => true , 'data' => $charge->id]);
    }

    public function confirm_refund(Request $request)
    {
        $data = $request->all();
        $data['refund_status'] = 'COMPLETE';
        Refund::find($request->refund_id)->update($data);
        return response(['success' => true]);
    }

    public function user_bank_details($id)
    {
        $user = User::find($id);
        return response(['success' => true , 'data' => $user]);
    }
}
