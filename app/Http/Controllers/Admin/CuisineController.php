<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Imports\CusineImport;
use App\Mail\Verification;
use App\Models\Cuisine;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Gate;
use DB;
use Mail;
use Symfony\Component\HttpFoundation\Response;

class CuisineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('cuisine_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $cuisines = Cuisine::orderBy('id','DESC')->get();
        return view('admin.cuisine.cuisine',compact('cuisines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('cuisine_add'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.cuisine.create_cuisine');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
        ['name' => 'required','unique:cuisine'],
        [
            'name.required' => 'The Name Of Cuisine Field Is Required',
        ]);
        $data = $request->all();
        $data['status'] = $request->has('status') == 1 ? 1 : 0;
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
        Cuisine::create($data);
        return redirect('admin/cuisine')->with('msg','Cuisine created successfully..!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cuisine  $cuisine
     * @return \Illuminate\Http\Response
     */
    public function show(Cuisine $cuisine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cuisine  $cuisine
     * @return \Illuminate\Http\Response
     */

    public function edit(Cuisine $cuisine)
    {
        abort_if(Gate::denies('cuisine_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.cuisine.edit_cuisine',compact('cuisine'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cuisine  $cuisine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cuisine $cuisine)
    {
        $request->validate(
        ['name' => 'required','unique:cuisine,name,' . $cuisine . ',id'],
        [
            'name.required' => 'The Name Of Cuisine Field Is Required',
        ]);
        $data = $request->all();
        if ($file = $request->hasfile('image'))
        {
            $request->validate(
            ['image' => 'max:1000'],
            [
                'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            (new CustomController)->deleteImage(DB::table('cuisine')->where('id', $cuisine->id)->value('image'));
            $data['image'] = (new CustomController)->uploadImage($request->image);
        }
        $cuisine->update($data);
        return redirect('admin/cuisine')->with('msg','Cuisine updated successfully..!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cuisine  $cuisine
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cuisine $cuisine)
    {
        $cusine = Cuisine::find($cuisine);
        $vendors = Vendor::all();
        foreach ($vendors as $value)
        {
            $cIds = explode(',',$value->cuisine_id);
            if(count($cIds) > 0)
            {
                if (($key = array_search($cuisine->id, $cIds)) !== false)
                {
                    return response(['success' => false , 'data' => __('this cuisines connected with vendor first delete vendor')]);
                }
            }
        }
        (new CustomController)->deleteImage(DB::table('cuisine')->where('id', $cuisine->id)->value('image'));
        $cuisine->delete();
        return response(['success' => true]);
    }

    public function change_status(Request $request)
    {
        $data = Cuisine::find($request->id);
        $data->status = $data->status == 1 ? 0 : 1;
        $data->save();
        return response(['success' => true]);
    }
}
