<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Mail\AdminForgotPassword;
use App\Models\Admin;
use App\Models\BussinessSetting;
use App\Models\Country;
use App\Models\Currency;
use App\Models\NotificationTemplate;
use App\Models\Feedback;
use App\Models\GeneralSetting;
use App\Models\Language;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Gate;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\HttpFoundation\Response;
use DB;
use LicenseBoxExternalAPI;
use Session;


class AdminController extends Controller
{
    public function showLogin()
    {
        if (env('DB_DATABASE'))
            return view('auth.login');
        else
            return view('first_page');
    }

    public function confirm_login(Request $request)
    {
        $request->validate([
            'email_id' => 'bail|required|email',
            'password' => 'bail|required',
        ]);
        if(Auth::attempt(['email_id' => request('email_id'), 'password' => request('password')]))
        {
            $user = Auth::user()->load('roles');
            if ($user->roles->contains('title', 'user') == true)
            {
                Auth::logout();
                return redirect()->back()->withErrors('Only admin can login');
            }
            if ($user->roles->contains('title', 'admin'))
            {
                $data = GeneralSetting::find(1);
                $data->license_verify = 1;
                $data->save();
                $api = new LicenseBoxExternalAPI();
                $res = $api->verify_license();
                if ($res['status'] != true)
                {
                    $data->license_verify = 0;
                    $data->save();
                }
                else
                {
                    $data->license_verify = 1;
                    $data->save();
                }
                return redirect('admin/home');
            }
        }
        return redirect()->back()->withErrors('this credential does not match our record')->withInput();
    }

    public function admin_profile()
    {
        abort_if(Gate::denies('admin_profile_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $admin = Auth::user();
        return view('admin.admin setting.admin_profile',compact('admin'));
    }

    public function update_admin_profile(Request $request)
    {
        $data = $request->all();
        $id = User::find($data['id']);
        if ($file = $request->hasfile('image'))
        {
            $request->validate(
            ['image' => 'max:1000'],
            [
                'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            (new CustomController)->deleteImage(DB::table('users')->where('id', $data['id'])->value('image'));
            $data['image'] = (new CustomController)->uploadImage($request->image);
        }
        $id->update($data);
        return redirect()->back()->with('msg','Admin profile update successfully..!!');
    }

    public function admin_forgot_password(Request $request)
    {
        $user = User::where('email_id',$request->email)->first();
        $password = mt_rand(100000, 999999);
        if($user)
        {
            $passwordTemplate = NotificationTemplate::where('title','forgot password')->first();
            $mail_content = $passwordTemplate->mail_content;
            $detail['password'] = $password;
            $detail['user_name'] = $user->name;
            $data = ["{password}", "{user_name}"];
            if($user)
            {
                $user->password = Hash::make($password);
                $user->save();
                $message1 = str_replace($data, $detail, $mail_content);
                try
                {
                    Mail::to($user->email_id)->send(new AdminForgotPassword($message1));
                }
                catch (\Throwable $th)
                {}
                return redirect()->back()->with('msg','Your Password Send Into Your Email..!!');
            }
        }
        else
        {
            return redirect()->back()->with('errormsg','Ooops.. User Not Found..!!');
        }
    }

    public function change_password(Request $request)
    {
        $request->validate([
            'old_password' => 'required|min:6',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password|min:6',
        ]);
        $data = $request->all();
        $id = auth()->user();
        if(Hash::check($data['old_password'], $id->password) == true)
        {
            $id->password = Hash::make($data['password']);
            $id->save();
            return redirect('admin/home')->with('msg','Password Update Successfully...!!');
        }
        else
        {
            return redirect()->back()->with('message','Old password does not match');
        }
    }

    public function forgot_password()
    {
        return view('admin.admin setting.admin_forgot_password');
    }

    public function feedback()
    {
        abort_if(Gate::denies('feedback_support'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $feedbacks = Feedback::get();
        return view('admin.admin setting.feedback',compact('feedbacks'));
    }

    public function change_language($name)
    {
        App::setLocale($name);
        session()->put('locale', $name);
        $direction = Language::where('name',$name)->first()->direction;
        session()->put('direction', $direction);
        return redirect()->back();
    }

    public function saveEnvData(Request $request)
    {

        $request->validate([
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:6',
        ]);
        $data = $request->all();
        $data['DB_HOST'] = $request->db_host;
        $data['DB_DATABASE'] = $request->db_name;
        $data['DB_USERNAME'] = $request->db_user;
        $data['DB_PASSWORD'] = $request->db_pass;
        $envFile = app()->environmentFilePath();
        if ($envFile)
        {
            $str = file_get_contents($envFile);
            if (count($data) > 0) {
                foreach ($data as $envKey => $envValue) {
                    $str .= "\n";
                    $keyPosition = strpos($str, "{$envKey}=");
                    $endOfLinePosition = strpos($str, "\n", $keyPosition);
                    $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                    if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                        $str .= "{$envKey}={$envValue}\n";
                    } else {
                        $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                    }
                }
            }
            $str = substr($str, 0, -1);
            if (!file_put_contents($envFile, $str)) {
                return response()->json(['data' => null, 'success' => false], 200);
            }
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            User::first()->update(['email_id' => $request->email , 'password' => Hash::make($request->password)]);
            return response()->json(['success' => true], 200);
        }
    }

    public function saveAdminData(Request $request)
    {

        $request->validate([
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:6',
        ]);

        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        User::first()->update(['email_id'=>$request->email,'password'=>Hash::make($request->password)]);
        GeneralSetting::find(1)->update(['license_code' => $request->license_code , 'client_name' => $request->client_name , 'license_verify' => 1]);
        return response()->json(['data' => url('/login'), 'success' => true], 200);
    }

    public function download_pdf($file_name)
    {
        $pathToFile = public_path(). "/sample_excel"."/".$file_name;
        $name = $file_name;
        $headers = array('Content-Type: application/pdf',);
        return response()->download($pathToFile, $name, $headers);
    }

    public function license()
    {
        return view('license.license');
    }
}
