<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Models\PromoCode;
use App\Models\User;
use App\Models\Vendor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use DB;

class PromoCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('promo_code_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $PromoCodes = PromoCode::orderBy('id','DESC')->get();
        return view('admin.promo code.promo_code',compact('PromoCodes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('promo_code_add'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $vendors = Vendor::get();
        $users = User::whereHas('roles', function($q)
        {
            $q->where('title','user');
        })->get();
        return view('admin.promo code.create_promo_code',compact('vendors','users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'promo_code' => 'required',
            'discountType' => 'required',
            'max_disc_amount' => 'required_if:isFlat,0',
            'start_end_date' => 'required',
            'max_count' => 'required',
            'min_order_amount' => 'required',
            'max_user' => 'bail|required|numeric',
            'description' => 'required',
            'vendor_id' => 'required',
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
            $data['image'] = 'product_default.jpg';
        $data['status'] = $request->has('status') ? 1 : 0;
        $data['display_customer_app'] = $request->has('display_customer_app') ? 1 : 0;
        if(isset($data['isFlat']))
        {
            $request->validate([
                'flatDiscount' => 'required',
            ]);
            $data['isFlat'] = 1;
        }
        else
        {
            $data['isFlat'] = 0;
        }
        if (isset($data['customer_id']))
        {
            $data['customer_id'] = implode(',',$data['customer_id']);
        }
        if (isset($data['vendor_id']))
        {
            $data['vendor_id'] = implode(',',$data['vendor_id']);
        }
        PromoCode::create($data);
        return redirect('admin/promo_code')->with('msg','promo code created successfully..!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function show(PromoCode $promoCode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function edit(PromoCode $promoCode)
    {
        abort_if(Gate::denies('promo_code_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $vendors = Vendor::get();
        $users = User::whereHas('roles', function($q)
        {
            $q->where('title','user');
        })->get();
        return view('admin.promo code.edit_promo_code',compact('promoCode','vendors','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PromoCode $promoCode)
    {
        $request->validate([
            'name' => 'required',
            'promo_code' => 'required',
            'discountType' => 'required',
            'max_disc_amount' => 'required_if:isFlat,0',
            'update_start_end_date' => 'required',
            'max_count' => 'required',
            'min_order_amount' => 'required',
            'max_user' => 'bail|required|numeric',
            'description' => 'required'
        ]);
        $data = $request->all();
        $data['start_end_date'] = $request->update_start_end_date;
        if ($file = $request->hasfile('image'))
        {
            $request->validate(
            ['image' => 'max:1000'],
            [
                'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            (new CustomController)->deleteImage(DB::table('promo_code')->where('id', $promoCode->id)->value('image'));
            $data['image'] = (new CustomController)->uploadImage($request->image);
        }
        if(isset($data['isFlat']))
        {
            $request->validate([
                'flatDiscount' => 'required',
            ]);
            $data['isFlat'] = 1;
        }
        else
        {
            $data['isFlat'] = 0;
        }
        if (isset($data['customer_id']))
        {
            $data['customer_id'] = implode(',',$data['customer_id']);
        }
        if (isset($data['vendor_id']))
        {
            $data['vendor_id'] = implode(',',$data['vendor_id']);
        }
        $data['display_customer_app'] = $request->has('display_customer_app') ? 1 : 0;
        $promoCode->update($data);
        return redirect('admin/promo_code')->with('msg','promo code updated successfully..!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function destroy(PromoCode $promo_code)
    {
        (new CustomController)->deleteImage(DB::table('promo_code')->where('id', $promo_code->id)->value('image'));
        $promo_code->delete();
        return response(['success' => true]);
    }

    public function change_status(Request $request)
    {
        $data = PromoCode::find($request->id);
        $data->status = $data->status == 1 ? 0 : 1;
        $data->save();
        return response(['success' => true]);
    }
}
