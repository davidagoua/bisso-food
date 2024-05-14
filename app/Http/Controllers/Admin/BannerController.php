<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Models\Banner;
use App\Models\Vendor;
use Illuminate\Http\Request;
use DB;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('banner_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $banners = Banner::get();
        return view('admin.banner.banner',compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('banner_add'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.banner.create_banner');
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
        Banner::create($data);
        return redirect('admin/banner')->with('msg','Banner created successfully..!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        abort_if(Gate::denies('banner_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.banner.edit_banner',compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        $data = $request->all();
        if ($file = $request->hasfile('image'))
        {
            $request->validate(
            ['image' => 'max:1000'],
            [
                'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            (new CustomController)->deleteImage(DB::table('banner')->where('id', $banner->id)->value('image'));
            $data['image'] = (new CustomController)->uploadImage($request->image);
        }
        $banner->update($data);
        return redirect('admin/banner')->with('msg','Banner updated successfully..!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        (new CustomController)->deleteImage(DB::table('banner')->where('id', $banner->id)->value('image'));
        $banner->delete();
        return response(['success' => true]);
    }

    public function change_status(Request $request)
    {
        $data = Banner::find($request->id);
        $data->status = $data->status == 1 ? 0 : 1;
        $data->save();
        return response(['success' => true]);
    }
}