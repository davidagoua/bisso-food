<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Models\Language;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use DB;
use File;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('language_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $languages = Language::orderBy('id','DESC')->get();
        return view('admin.language.language',compact('languages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('language_add'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.language.create_language');
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
        $request->validate([
            'name' => 'required',
            'image' => 'required',
            'direction' => 'required',
            'file' => 'required'
        ]);
        if ($file = $request->hasfile('image'))
        {
            $request->validate(
            ['image' => 'max:1000'],
            [
                'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            $file = $request->file('image');
            $fileName = $request->name;
            $path = public_path('/images/upload/');
            $file->move($path, $fileName.".png");
            $data['image'] = $fileName.".png";
        }
        if ($file = $request->hasfile('file'))
        {
            $file = $request->file('file');
            $fileName = $request->name;
            $path = resource_path('/lang');
            $file->move($path, $fileName.'.json');
            $data['file'] = $fileName.".json";;
        }
        if(isset($data['status']))
        {
            $data['status'] = 1;
        }
        else
        {
            $data['status'] = 0;
        }
        Language::create($data);
        return redirect('admin/language');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function show(Language $language)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function edit(Language $language)
    {
        return view('admin.language.edit_language',compact('language'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Language $language)
    {
        $data = $request->all();
        $request->validate([
            'name' => 'required',
            'direction' => 'required',
        ]);
        if ($file = $request->hasfile('image'))
        {
            $request->validate(
            ['image' => 'max:1000'],
            [
                'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            $file = $request->file('image');
            $fileName = $request->name;
            $path = public_path('/images/upload/');
            $file->move($path, $fileName.".png");
            $data['image'] = $fileName.".png";
        }
        if ($file = $request->hasfile('file'))
        {
            $file = $request->file('file');
            $fileName = $request->name;
            $path = resource_path('/lang');
            $file->move($path, $fileName.'.json');
            $data['file'] = $fileName.".json";;
        }
        if(isset($data['status']))
        {
            $data['status'] = 1;
        }
        else
        {
            $data['status'] = 0;
        }
        $language->update($data);
        return redirect('admin/language');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function destroy(Language $language)
    {
        if(File::exists(resource_path('lang/'.$language->file))){
            File::delete(resource_path('lang/'.$language->file));
        }
        (new CustomController)->deleteImage(DB::table('language')->where('id', $language->id)->value('image'));
        $language->delete();
        return response(['success' => true]);
    }

    public function change_status(Request $request)
    {
        $data = Language::find($request->id);
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
