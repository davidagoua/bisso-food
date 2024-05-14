<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\Submenu;
use App\Models\SubmenuCusomizationType;
use Illuminate\Http\Request;

class SubmenuCustomizationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $submenu = Submenu::find($id);
        $currency_symbol = GeneralSetting::first()->currency_symbol;
        $custimization_types = SubmenuCusomizationType::where('submenu_id',$id)->get();
        return view('admin.custimization submenu.custimization_submenu',compact('submenu','custimization_types','currency_symbol'));
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
        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;
        SubmenuCusomizationType::create($data);
        return redirect()->back()->with('msg','Custimization type Added successfully');
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
        $data = SubmenuCusomizationType::find($id);
        return response(['success' => true , 'data' => $data]);
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
        $id = SubmenuCusomizationType::find($id);
        $id->update($request->all());
        return redirect()->back()->with('msg','Custimization type update successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = SubmenuCusomizationType::find($id);
        $id->delete();
        return response(['success' => true]);
    }

    public function updateItem(Request $request)
    {
        $data = $request->all();
        $id = SubmenuCusomizationType::find($data['custimization_type_id']);
        $name = str_replace(' ', '_', strtolower($id->name));
        $master = array();
        $items = $data['name'.$name];
        $radioCheck = 0;
        for ($i = 0; $i < count($items); $i++)
        {
            if(isset($data['isdefault_'.$name]))
                if ($data['isdefault_'.$name] == $i)
                    $radioCheck = 1;
        }

        for ($i = 0; $i < count($items); $i++)
        {
            $reqData['name'] = $data['name'.$name][$i];
            $reqData['name'] = $data['name'.$name][$i];
            $reqData['price'] = $data['price'][$i];
            if($radioCheck == 0)
                $reqData['isDefault'] = $i == 0 ? 1 : 0;
            else
            {
                if ($data['isdefault_'.$name] == $i)
                    $reqData['isDefault'] = 1;
                else
                    $reqData['isDefault'] = 0;
            }
            if(isset($data['status'.$i]))
                $reqData['status'] = 1;
            else
                $reqData['status'] = 0;
            array_push($master,$reqData);
        }
        $id->custimazation_item = json_encode($master);
        $id->save();
        return redirect()->back();
    }
}
