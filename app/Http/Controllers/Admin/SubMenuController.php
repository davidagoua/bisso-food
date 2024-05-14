<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Imports\SubmenuImport;
use App\Models\Order;
use App\Models\OrderChild;
use App\Models\Submenu;
use Illuminate\Http\Request;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class SubMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
            'price' => 'bail|required|numeric'
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

        $data['status'] = $request->has('status') ? 1 : 0;
        Submenu::create($data);
        return redirect()->back()->with('msg','submenu created successfully..!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Submenu  $submenu
     * @return \Illuminate\Http\Response
     */
    public function show(Submenu $submenu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Submenu  $submenu
     * @return \Illuminate\Http\Response
     */
    public function edit(Submenu $submenu)
    {
        return response(['success' => true , 'data' => $submenu]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Submenu  $submenu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Submenu $submenu)
    {
        $data = $request->all();
        $request->validate([
            'price' => 'bail|required|numeric'
        ]);
        if ($file = $request->hasfile('image'))
        {
            $request->validate(
            ['image' => 'max:1000'],
            [
                'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            (new CustomController)->deleteImage(DB::table('submenu')->where('id', $submenu->id)->value('image'));
            $data['image'] = (new CustomController)->uploadImage($request->image);
        }
        $submenu->update($data);
        return redirect()->back()->with('msg','submenu updated successfully..!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Submenu  $submenu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Submenu $submenu)
    {
        $ids = OrderChild::where('item',$submenu->id)->get(['order_id'])->makeHidden(['itemName','custimization']);
        if(count($ids) > 0)
        {
            foreach ($ids as $id)
            {
                $orderChild = OrderChild::whereIn('order_id',$id)->get();
                if(count($orderChild) > 1)
                {
                    OrderChild::where('item',$submenu->id)->delete();
                }
                else
                {
                    Order::whereIn('id',$id)->delete();
                }
            }
        }
        (new CustomController)->deleteImage(DB::table('submenu')->where('id', $submenu->id)->value('image'));
        $submenu->delete();
        return response(['success' => true]);
    }

    public function change_status(Request $request)
    {
        $data = Submenu::find($request->id);
        $data->status = $data->status == 1 ? 0 : 1;
        $data->save();
        return response(['success' => true]);
    }

    public function submenu_import($vendor_id)
    {
        Excel::import(new SubmenuImport($vendor_id),request()->file('file'));
        return back();
    }
}
