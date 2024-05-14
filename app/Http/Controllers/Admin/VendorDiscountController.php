<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Models\GeneralSetting;
use App\Models\Vendor;
use App\Models\VendorDiscount;
use Illuminate\Http\Request;
use DB;

class VendorDiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $discounts = VendorDiscount::where('vendor_id',$id)->get();
        $currency = GeneralSetting::find(1)->currency_symbol;
        return view('admin.vendor discount.vendor_discount',compact('id','discounts','currency'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        return view('admin.vendor discount.create_vendor_discount',compact('id'));
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
            'discount' => 'bail|required|numeric',
            'max_discount_amount' => 'bail|required|numeric',
            'min_item_amount' => 'bail|required|numeric',
            'type' => 'bail|required',
            'description' => 'bail|required',
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
        $id = VendorDiscount::create($data);
        return redirect('admin/vendor_discount/'.$id->vendor_id)->with('msg','vendor discount added successfully..!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VendorDiscount  $vendorDiscount
     * @return \Illuminate\Http\Response
     */
    public function show(VendorDiscount $vendorDiscount)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VendorDiscount  $vendorDiscount
     * @return \Illuminate\Http\Response
     */
    public function edit(VendorDiscount $vendorDiscount)
    {
        return view('admin.vendor discount.edit_vendor_discount',compact('vendorDiscount'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VendorDiscount  $vendorDiscount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VendorDiscount $vendorDiscount)
    {
        $request->validate([
            'discount' => 'bail|required|numeric',
            'max_discount_amount' => 'bail|required|numeric',
            'min_item_amount' => 'bail|required|numeric',
            'type' => 'bail|required',
            'description' => 'bail|required',
        ]);
        $data = $request->all();
        if ($file = $request->hasfile('image'))
        {
            $request->validate(
            ['image' => 'max:1000'],
            [
                'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            (new CustomController)->deleteImage(DB::table('vendor_discount')->where('id', $vendorDiscount->id)->value('image'));
            $data['image'] = (new CustomController)->uploadImage($request->image);
        }
        $vendorDiscount->update($data);
        return redirect('admin/vendor_discount/'.$vendorDiscount->vendor_id)->with('msg','vendor discount updated successfully..!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VendorDiscount  $vendorDiscount
     * @return \Illuminate\Http\Response
     */
    public function destroy(VendorDiscount $vendorDiscount)
    {

    }

}
