<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationTemplate;
use Illuminate\Http\Request;
use App;
use App\Models\User;
use App\Models\Vendor;
use Config;
use OneSignal;

class NotificationTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (session()->has('locale'))
        {
            $lang = session()->get('locale');
            if ($lang == "spanish") 
            {
                $data = NotificationTemplate::all();
                foreach ($data as $value) 
                {
                    $value->notification_content = $value->spanish_notification_content;
                    $value->mail_content = $value->spanish_mail_content;
                }
            }
            else
                $data = NotificationTemplate::all();
        }
        else
            $data = NotificationTemplate::all();
        return view('admin.notification template.notification_template',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.notification template.create_notification_template');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        NotificationTemplate::create($request->all());
        return redirect('admin/notification_template')->with('msg','notification template created successfully..!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NotificationTemplate  $notificationTemplate
     * @return \Illuminate\Http\Response
     */
    public function show(NotificationTemplate $notificationTemplate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NotificationTemplate  $notificationTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(NotificationTemplate $notificationTemplate)
    {
        if (session()->has('locale')) 
        {
            $lang = session()->get('locale');
            if ($lang == "spanish") 
            {
                $notificationTemplate->notification_content = $notificationTemplate->spanish_notification_content;
                $notificationTemplate->mail_content = $notificationTemplate->spanish_mail_content;
            }
        }
        return response(['success' => true,'data' => $notificationTemplate]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NotificationTemplate  $notificationTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NotificationTemplate $notificationTemplate)
    {
        $data = $request->all();
        if (session()->has('locale')) 
        {
            $lang = session()->get('locale');
            if ($lang == "spanish") 
            {
                $notificationTemplate->spanish_notification_content = $data['notification_content'];
                $notificationTemplate->spanish_mail_content = $data['mail_content'];
                $notificationTemplate->save();
            }
            else
                $notificationTemplate->update($data);
        }
        else
            $notificationTemplate->update($data);
        return redirect('admin/notification_template')->with('msg','notification template updated successfully..!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NotificationTemplate  $notificationTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(NotificationTemplate $notificationTemplate)
    {
        //
    }

    public function send_notification()
    {
        $users = User::whereHas('roles', function($q)
        {
            $q->where('title','user');
        })->get();
        $vUsers = User::whereHas('roles', function($q)
        {
            $q->where('title','vendor');
        })->get();
        $vendors = [];
        foreach ($vUsers as $vUser) {
            if(Vendor::where('user_id',$vUser->id)->first())
                array_push($vendors,Vendor::where('user_id',$vUser->id)->first());
        }
        return view('admin.notification template.send_notification',compact('users','vendors'));
    }

    public function send_notification_user(Request $request)
    {
        $request->validate([
            'title' => 'bail|required',
            'message' => 'bail|required',
            'user_id' => 'bail|required',
        ]);
        foreach ($request->user_id as $id) 
        {
            $user = User::find($id);
            try {
                Config::set('onesignal.app_id', env('customer_app_id'));
                Config::set('onesignal.rest_api_key', env('customer_api_key'));
                Config::set('onesignal.user_auth_key', env('customer_auth_key'));
                OneSignal::sendNotificationToUser(
                    $request->message,
                    $user->device_token,
                    $url = null,
                    $data = null,
                    $buttons = null,
                    $schedule = null,
                    $request->title
                );
            } catch (\Throwable $th) {
                //throw $th;
            }
            
        }
        return redirect()->back();
    }

    public function send_notification_vendor(Request $request)
    {
        $request->validate([
            'title' => 'bail|required',
            'message' => 'bail|required',
            'vendor_id' => 'bail|required',
        ]);
        foreach ($request->vendor_id as $id) 
        {
            $vendor = Vendor::find($id);
            $user = User::find($vendor->user_id);
            try {
                Config::set('onesignal.app_id', env('vendor_app_id'));
                Config::set('onesignal.rest_api_key', env('vendor_api_key'));
                Config::set('onesignal.user_auth_key', env('vendor_auth_key'));
                OneSignal::sendNotificationToUser(
                    $request->message,
                    $user->device_token,
                    $url = null,
                    $data = null,
                    $buttons = null,
                    $schedule = null,
                    $request->title
                );
            } catch (\Throwable $th) {
                //throw $th;
            }
            
        }
        return redirect()->back();
    }
}
