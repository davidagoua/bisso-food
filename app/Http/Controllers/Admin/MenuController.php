<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderChild;
use App\Models\Submenu;
use Illuminate\Http\Request;
use DB;

class MenuController extends Controller
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
        $request->validate([
            'name' => 'required',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;
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
        Menu::create($data);
        return redirect()->back()->with('msg','menu created successfully..!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        $menu['submenu'] = Submenu::where('menu_id',$menu->id)->get();
        return view('admin.menu.menu',compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        return response(['success' => true , 'data' => $menu]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
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
            (new CustomController)->deleteImage(DB::table('menu')->where('id', $menu->id)->value('image'));
            $data['image'] = (new CustomController)->uploadImage($request->image);
        }
        $menu->update($data);
        return redirect()->back()->with('msg','menu updated successfully..!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        (new CustomController)->deleteImage(DB::table('menu')->where('id', $menu->id)->value('image'));
        $submenus = Submenu::where('menu_id',$menu->id)->get();
        foreach ($submenus as $submenu)
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
        }
        $menu->delete();
        return response(['success' => true]);
    }

    public function change_status(Request $request)
    {
        $data = Menu::find($request->id);
        if($data->status == 0)
        {
            $data->status = 1;
            $data->save();
            return response(['success' => true]);
        }
        if($data->status == 1)
        {
            $data->status = 0;
            $data->save();
            return response(['success' => true]);
        }
    }
}
