<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderSetting;
use Carbon\Carbon;
use File;

class CustomController extends Controller
{
    public function uploadImage($image)
    {
        $file = $image;
        $fileName = uniqid() . '.' .$image->getClientOriginalExtension();
        $path = public_path() . '/images/upload';
        $file->move($path, $fileName);
        return $fileName;
    }

    public function deleteImage($file_name)
    {
        if($file_name != 'product_default.jpg' && $file_name != 'noimage.png' && $file_name != 'vendor-logo.png' && $file_name != 'impageplaceholder.png')
        {
            if(File::exists(public_path('images/upload/'.$file_name))){
                File::delete(public_path('images/upload/'.$file_name));
            }
            return true;
        }
    }

    public function cancel_max_order()
    {
        $cancel_time = OrderSetting::first()->vendor_order_max_time;
        $dt = Carbon::now(env('timezone'));
        $formatted = $dt->subMinute($cancel_time);
        $cancel_orders = Order::where([['created_at', '<=', $formatted],['order_status','PENDING']])->get();
        foreach ($cancel_orders as $cancel_order)
        {
            $cancel_order->order_status = 'CANCEL';
            $cancel_order->save();
        }
        return true;
    }

    public function driver_cancel_max_order()
    {
        $cancel_time = OrderSetting::first()->driver_order_max_time;
        $dt = Carbon::now();
        $formatted = $dt->subMinute($cancel_time);
        $cancel_orders = Order::where([['created_at', '<=', $formatted],['order_status','ACCEPT']])->get();
        foreach ($cancel_orders as $cancel_order)
        {
            $cancel_order->order_status = 'CANCEL';
            $cancel_order->save();
        }
        return true;
    }

    public function base64Image($image)
    {
        $img = $image;
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data1 = base64_decode($img);
        $Iname = uniqid();
        $file = public_path('/images/upload/') . $Iname . ".png";
        $success = file_put_contents($file, $data1);
        return $Iname . ".png";
    }
}
