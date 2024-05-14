<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryZone;
use App\Models\DeliveryZoneArea;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gate;
use Auth;

class DeliveryZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('delivery_zone_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $deliveryZones = DeliveryZone::all();
        return view('admin.delivery zone.delivery_zone',compact('deliveryZones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('delivery_zone_add'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.delivery zone.create_delivery_zone');
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
            'email' => 'bail|required|email|unique:delivery_zone',
            'admin_name' => 'bail|required',
            'contact' => 'bail|required|numeric|digits_between:6,12|unique:delivery_zone',
        ]);
        $data = $request->all();
        DeliveryZone::create($data);
        if(Auth::user()->load('roles')->roles->contains('title', 'admin'))
        {
            return redirect('admin/delivery_zone')->with('msg','Delivery Zone created successfully..!!');
        }
        if(Auth::user()->load('roles')->roles->contains('title', 'vendor'))
        {
            return redirect('vendor/deliveryZone')->with('msg','Delivery Zone created successfully..!!');
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
        $delivery_zone = DeliveryZone::find($id);
        return view('admin.delivery zone.edit_delivery_zone',compact('delivery_zone'));
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
            'name' => 'required',
            'admin_name' => 'bail|required',
            'contact' => 'bail|required|numeric|digits_between:6,12',
        ]);
        $data = $request->all();
        $id = DeliveryZone::find($id);
        $id->update($data);
        if(Auth::user()->load('roles')->roles->contains('title', 'admin'))
        {
            return redirect('admin/delivery_zone')->with('msg','Delivery Zone created successfully..!!');
        }
        if(Auth::user()->load('roles')->roles->contains('title', 'vendor'))
        {
            return redirect('vendor/deliveryZone')->with('msg','Delivery Zone created successfully..!!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeliveryZone $delivery_zone)
    {
        $delivery_zone->delete();
        return response(['success' => true]);
    }

    public function change_status(Request $request)
    {
        $data = DeliveryZone::find($request->id);
        $data->status = $data->status == 1 ? 0 : 1;
        $data->save();
        return response(['success' => true]);
    }
}
