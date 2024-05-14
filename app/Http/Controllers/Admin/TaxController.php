<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\cr;
use App\Models\Tax;
use App\Models\Vendor;
use App\Models\GeneralSetting;
use App\Models\Settle;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $taxs = Tax::orderBy('id','DESC')->get();
        $currency = GeneralSetting::first()->currency_symbol;
        return view('admin.tax.tax',compact('taxs','currency'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tax.create_tax');
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
            'name' => 'bail|required',
            'tax' => 'bail|required',
        ]);
        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;
        Tax::create($data);
        return redirect('admin/tax');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tax = Tax::find($id);
        return view('admin.tax.edit_tax',compact('tax'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = Tax::find($id);
        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;
        $id->update($data);
        return redirect('admin/tax');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tax = Tax::find($id);
        $tax->delete();
        return response(['success' => true]);

    }

    public function change_status(Request $request)
    {
        $data = Tax::find($request->id);
        $data->status = $data->status == 1 ? 0 : 1;
        $data->save();
        return response(['success' => true]);
    }
}
