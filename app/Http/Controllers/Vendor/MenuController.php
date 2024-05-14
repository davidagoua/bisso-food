<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Submenu;
use App\Models\Vendor;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendor = Vendor::where('user_id',auth()->user()->id)->first();
        $vendor['menu'] = Menu::where('vendor_id',$vendor->id)->get();
        return view('vendor.menu.menu',compact('vendor'));
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
    public function show(Menu $vendor_menu,Request $request)
    {
        $menu = $vendor_menu;
        if($request->has('filter'))
        {
            $vendor = Vendor::find($request->vendor_id);
            $submenu = Submenu::where('vendor_id',$vendor->id);
            $menu['submenu'] = $submenu->where('menu_id',$request->menu_id);
            $value = $request->filter;
            if($value == 'veg'){
                $submenu = $submenu->where('type','veg');
            }
            if($value == 'non_veg'){
                $submenu = $submenu->where('type','non_veg');
            }
            if($value == 'excel'){
                $submenu = $submenu->where('is_excel','1');
            }
            if($value == 'panel'){
                $submenu = $submenu->where('is_excel','0');
            }
            if($value == 'all'){
                $submenu = $submenu;
            }
            $menu['submenu'] = $submenu->get();
            $view = view('vendor.submenu.display_submenu',compact('menu'))->render();
            return response()->json(['html' => $view,'success' => true]);
        }
        else
        {
            $vendor = Vendor::where('user_id',auth()->user()->id)->first();
            $submenu = Submenu::where('vendor_id',$vendor->id);
            $menu['submenu'] = $submenu->where('menu_id',$menu->id);
            $menu['submenu'] = $submenu->get();
            return view('vendor.submenu.submenu',compact('menu'));
        }
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
}
