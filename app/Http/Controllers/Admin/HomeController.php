<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\Verification;
use Illuminate\Http\Request;
use Auth;
use App\Models\Vendor;
use App\Models\Order;
use Carbon\Carbon;
use App\Models\GeneralSetting;
use App\Models\Menu;
use App\Models\OrderChild;
use App\Models\Settle;
use App\Models\Submenu;
use Symfony\Component\HttpFoundation\Response;
use Gate;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        abort_if(Gate::denies('admin_dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $currency = GeneralSetting::first()->currency_symbol;
        $today_orders = Order::whereDate('created_at',Carbon::now())->count();
        $today_earnings = Settle::whereDate('created_at',Carbon::now())->sum('admin_earning');
        $total_earnings = Settle::sum('admin_earning');
        $total_orders = Order::count();
        $menus = Menu::count();
        $topItems = OrderChild::groupBy('item')->select('item', DB::raw('count(*) as total'))->orderBy('total','DESC')->get()->each->setAppends(['itemName']);
        foreach ($topItems as $item)
        {
            $item['type'] = Submenu::find($item->item)->type;
            $item['price'] = Submenu::find($item->item)->price;
            $item['menu_id'] = Submenu::find($item->item)->menu_id;
            $item['vendor'] = Vendor::find(Submenu::find($item->item)->vendor_id)->name;
        }
        return view('admin.home',compact('today_orders','menus','topItems','total_earnings','total_orders','today_earnings','currency','menus'));
    }

    public function topItems()
    {
        $topItems = OrderChild::groupBy('item')->select('item', DB::raw('count(*) as total'))->orderBy('total','DESC')->get()->each->setAppends(['itemName'])->take(4);
        return response()->json(['items' => $topItems]);
    }

    public function orderChart(Request $request)
    {
        if($request->has('start_date') && $request->has('end_date'))
        {
            $masterYear = array();
            $labelsYear = array();
            $now = Carbon::parse($request->get('end_date'));
            $past = Carbon::parse($request->get('start_date'));
            $c = $now->diffInDays($past);
            $loop = $c / 10;
            $data = [];
            while ($now->greaterThan($past))
            {
                $t = $past->copy();
                $t->addDay();
                $start = $t->toDateString();
                $past->addDays(7);
                if ($past->greaterThan($now))
                    $end = $now->toDateString();
                else
                    $end = $past->toDateString();
                array_push($labelsYear, Carbon::parse($start)->format('d').' - '.Carbon::parse($end)->format('d-M'));
                array_push($masterYear, Order::where([['created_at', '>=', $start.' '.'00:00:00'],['created_at', '<=', $end.' '.'23:59:59']])->count());
            }
            return response()->json(['data' => $masterYear, 'label' => $labelsYear]);
        }
        else
        {
            $masterYear = array();
            $labelsYear = array();
            array_push($masterYear, Order::whereMonth('created_at', Carbon::now())->count());

            for ($i = 1; $i <= 11; $i++)
            {
                if ($i >= Carbon::now()->month)
                    array_push($masterYear, Order::whereMonth('created_at',Carbon::now()->subMonths($i))->whereYear('created_at', Carbon::now()->subYears(1))->count());
                else
                    array_push($masterYear, Order::whereMonth('created_at', Carbon::now()->subMonths($i))->whereYear('created_at', Carbon::now()->year)->count());
            }
            array_push($labelsYear, Carbon::now()->format('M-y'));
            for ($i = 1; $i <= 11; $i++)
            {
                array_push($labelsYear, Carbon::now()->subMonths($i)->format('M-y'));
            }
        }
        return response()->json(['data' => $masterYear, 'label' => $labelsYear]);
    }

    public function earningChart(Request $request)
    {
        if($request->has('start_date') && $request->has('end_date'))
        {
            $masterYear = array();
            $labelsYear = array();
            $now = Carbon::parse($request->get('end_date'));
            $past = Carbon::parse($request->get('start_date'));
            $c = $now->diffInDays($past);
            $loop = $c / 10;
            $data = [];
            while ($now->greaterThan($past))
            {
                $t = $past->copy();
                $t->addDay();
                $start = $t->toDateString();
                $past->addDays(7);
                if ($past->greaterThan($now))
                    $end = $now->toDateString();
                else
                    $end = $past->toDateString();
                array_push($labelsYear, Carbon::parse($start)->format('d').' - '.Carbon::parse($end)->format('d-M'));
                array_push($masterYear, Settle::where([['created_at', '>=', $start.' '.'00:00:00'],['created_at', '<=', $end.' '.'23:59:59']])->sum('admin_earning'));
            }
            return response()->json(['data' => $masterYear, 'label' => $labelsYear]);
        }
        else
        {
            $userYear = array();
            $Userlabels = array();

            array_push($userYear, Settle::whereMonth('created_at', Carbon::now())->sum('admin_earning'));
            for ($i = 1; $i <= 11; $i++)
            {
                if ($i >= Carbon::now()->month)
                    array_push($userYear, Settle::whereMonth('created_at',Carbon::now()->subMonths($i))->whereYear('created_at', Carbon::now()->subYears(1))->sum('admin_earning'));
                else
                    array_push($userYear, Settle::whereMonth('created_at', Carbon::now()->subMonths($i))->whereYear('created_at', Carbon::now()->year)->sum('admin_earning'));
            }

            array_push($Userlabels, Carbon::now()->format('M-y'));
            for ($i = 1; $i <= 11; $i++) {
                array_push($Userlabels, Carbon::now()->subMonths($i)->format('M-y'));
            }
            return response()->json(['data' => $userYear, 'label' => $Userlabels]);
        }
    }

    public function avarageItems()
    {
        $label = array();
        $lastMonthTotalOrder = [];
        $LastMonthavarageTime = [];
        $currentMonthTotalOrder = [];
        $currentMonthAvarageTime = [];
        $vendors = Vendor::whereStatus(1)->get();
        foreach ($vendors as $vendor)
        {
            $lastMonthOrders = Order::where([['vendor_id',$vendor->id],['order_status','COMPLETE']])->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->get();
            $currentMonthOrders = Order::where([['vendor_id',$vendor->id],['order_status','COMPLETE']])->whereMonth('created_at', Carbon::now()->month)->get();
            foreach ($lastMonthOrders as $lastMonthOrder)
            {
                if ($lastMonthOrder->order_start_time != null && $lastMonthOrder->order_end_time != null)
                {
                    $diff_in_minutes = Carbon::parse($lastMonthOrder->order_start_time)->diffInMinutes(Carbon::parse($lastMonthOrder->order_end_time));
                    array_push($lastMonthTotalOrder,$diff_in_minutes);
                }
            }
            if (count($lastMonthTotalOrder) > 0)
            {
                $lastMonthOrderSum = array_sum($lastMonthTotalOrder);
                $temp = intval($lastMonthOrderSum) / intval(count($lastMonthTotalOrder));
                array_push($LastMonthavarageTime,intval($temp));
            }

            foreach ($currentMonthOrders as $currentMonthOrder)
            {
                if ($currentMonthOrder->order_start_time != null && $currentMonthOrder->order_end_time != null)
                {
                    $diff_in_minutes = Carbon::parse($currentMonthOrder->order_start_time)->diffInMinutes(Carbon::parse($currentMonthOrder->order_end_time));
                    array_push($currentMonthTotalOrder,$diff_in_minutes);
                }
            }
            if (count($currentMonthTotalOrder) > 0)
            {
                $currentMonthOrderSum = array_sum($currentMonthTotalOrder);
                $temp = intval($currentMonthOrderSum) / intval(count($currentMonthTotalOrder));
                array_push($currentMonthAvarageTime,intval($temp));
            }
            array_push($label,$vendor->name);
        }
        return ['currentMonth' => $currentMonthAvarageTime , 'lastMonth' => $currentMonthAvarageTime , 'label' => $label];
    }
}
