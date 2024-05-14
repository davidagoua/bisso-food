<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Cuisine;
use App\Models\DeliveryPerson;
use App\Models\DeliveryZone;
use App\Models\DeliveryZoneArea;
use App\Models\Faq;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderChild;
use App\Models\PromoCode;
use App\Models\Submenu;
use App\Models\Tax;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorDiscount;
use Illuminate\Http\Request;
use DB;

class multiDeleteController extends Controller
{
    public function cuisine_delete(Request $request)
    {
        $ids = explode(',',$request->ids);
        $cuisines = Cuisine::whereIn('id',$ids)->get();
        foreach ($cuisines as $cuisine)
        {
            $vendors = Vendor::all();
            foreach ($vendors as $value)
            {
                $cIds = explode(',',$value->cuisine_id);
                if(count($cIds) > 0)
                {
                    if (($key = array_search($cuisine->id, $cIds)) !== false)
                    {
                        return response(['success' => false , 'data' => __('This cuisine is connected with one or more vendors, First remove from their profiles')]);
                    }
                }
            }
            (new CustomController)->deleteImage(DB::table('cuisine')->where('id', $cuisine->id)->value('image'));
            $cuisine->delete();
        }
        return response(['success' => true]);
    }

    public function vendor_delete(Request $request)
    {
        $ids = explode(',',$request->ids);
        $vendors = Vendor::whereIn('id',$ids)->get();
        foreach ($vendors as $vendor) {
            $promoCodes = PromoCode::all();
            foreach ($promoCodes as $promoCode)
            {
                $vIds = explode(',',$promoCode->vendor_id);
                if(count($vIds) > 0)
                {
                    if (($key = array_search($vendor->id, $vIds)) !== false)
                    {
                        unset($vIds[$key]);
                        $promoCode->vendor_id = implode(',',$vIds);
                    }
                    $promoCode->save();
                }
            }

            $delivery_zone_areas = DeliveryZoneArea::all();
            foreach ($delivery_zone_areas as $delivery_zone_area)
            {
                $vIds = explode(',',$delivery_zone_area->vendor_id);
                if(count($vIds) > 0)
                {
                    if (($key = array_search($vendor->id, $vIds)) !== false)
                    {
                        unset($vIds[$key]);
                        $delivery_zone_area->vendor_id = implode(',',$vIds);
                    }
                    $delivery_zone_area->save();
                }
            }

            $users = User::all();
            foreach ($users as $user)
            {
                $favs = explode(',',$user->faviroute);
                if(count($favs) > 0)
                {
                    if (($key = array_search($vendor->id, $favs)) !== false)
                    {
                        unset($favs[$key]);
                        $users->faviroute = implode(',',$favs);
                    }
                    $user->save();
                }
            }

            $vendorUsers = User::where('vendor_id',$vendor->id)->get();
            foreach ($vendorUsers as $vendorUser) {
                $vendorUser->vendor_id = null;
                $vendorUser->save();
            }

            foreach (Menu::where('vendor_id',$vendor->id)->get() as $menu) {
                (new CustomController)->deleteImage(DB::table('menu')->where('id', $menu->id)->value('image'));
            }

            foreach (Submenu::where('vendor_id',$vendor->id)->get() as $submenu) {
                (new CustomController)->deleteImage(DB::table('submenu')->where('id', $submenu->id)->value('image'));
            }

            User::find($vendor->user_id)->delete();
        }
        return response(['success' => true]);
    }

    public function submenu_delete(Request $request)
    {
        $ids = explode(',',$request->ids);
        $submenus = Submenu::whereIn('id',$ids)->get();
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
            $submenu->delete();
        }
        return response(['success' => true]);
    }

    public function menu_delete(Request $request)
    {
        $ids = explode(',',$request->ids);
        $menus = Menu::whereIn('id',$ids)->get();
        foreach ($menus as $menu) {
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
        }
        return response(['success' => true]);
    }

    public function order_delete(Request $request)
    {
        $ids = explode(',',$request->ids);
        $orders = Order::whereIn('id',$ids)->get();
        foreach ($orders as $order)
        {
            $order->delete();
        }
        return response(['success' => true]);
    }

    public function delivery_person_delete(Request $request)
    {
        $ids = explode(',',$request->ids);
        $delivery_persons = DeliveryPerson::whereIn('id',$ids)->get();
        foreach ($delivery_persons as $delivery_person)
        {
            (new CustomController)->deleteImage(DB::table('delivery_person')->where('id', $delivery_person->id)->value('licence_doc'));
            (new CustomController)->deleteImage(DB::table('delivery_person')->where('id', $delivery_person->id)->value('national_identity'));
            (new CustomController)->deleteImage(DB::table('delivery_person')->where('id', $delivery_person->id)->value('image'));
            $delivery_person->delete();
        }
        return response(['success' => true]);
    }

    public function delivery_zone_delete(Request $request)
    {
        $ids = explode(',',$request->ids);
        $delivery_zones = DeliveryZone::whereIn('id',$ids)->get();
        foreach ($delivery_zones as $delivery_zone) {
            $delivery_zone->delete();
        }
        return response(['success' => true]);
    }

    public function promo_code_delete(Request $request)
    {
        $ids = explode(',',$request->ids);
        $promo_codes = PromoCode::whereIn('id',$ids)->get();
        foreach ($promo_codes as $promo_code)
        {
            (new CustomController)->deleteImage(DB::table('promo_code')->where('id', $promo_code->id)->value('image'));
            $promo_code->delete();
        }
        return response(['success' => true]);
    }

    public function user_multi_delete(Request $request)
    {
        $ids = explode(',',$request->ids);
        $users = User::whereIn('id',$ids)->get();
        foreach ($users as $user)
        {
            $promoCodes = PromoCode::all();
            foreach ($promoCodes as $promoCode)
            {
                $vIds = explode(',',$promoCode->customer_id);
                if(count($vIds) > 0)
                {
                    if (($key = array_search($promoCode->customer_id, $vIds)) !== false)
                    {
                        unset($vIds[$key]);
                        $promoCode->customer_id = implode(',',$vIds);
                    }
                    $promoCode->save();
                }
            }
            $user->delete();
        }
        return response(['success' => true]);
    }

    public function faq_multi_delete(Request $request)
    {
        $ids = explode(',',$request->ids);
        $faqs = Faq::whereIn('id',$ids)->get();
        foreach ($faqs as $faq)
        {
            $faq->delete();
        }
        return response(['success' => true]);
    }

    public function banner_multi_delete(Request $request)
    {
        $ids = explode(',',$request->ids);
        $banners = Banner::whereIn('id',$ids)->get();
        foreach ($banners as $banner) {
            (new CustomController)->deleteImage(DB::table('banner')->where('id', $banner->id)->value('image'));
            $banner->delete();
        }
        return response(['success' => true]);

    }

    public function tax_multi_delete(Request $request)
    {
        $ids = explode(',',$request->ids);
        $taxs = Tax::whereIn('id',$ids)->get();
        foreach ($taxs as $tax) {
            $tax->delete();
        }
        return response(['success' => true]);
    }

    public function vendor_discount_multi_delete(Request $request)
    {
        $ids = explode(',',$request->ids);
        $vendorDiscounts = VendorDiscount::whereIn('id',$ids)->get();
        foreach ($vendorDiscounts as $vendorDiscount) {
            $vendorDiscount->delete();
        }
        return response(['success' => true]);

    }
}
