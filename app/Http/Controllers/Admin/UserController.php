<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\PromoCode;
use App\Models\Role;
use App\Models\User;
use App\Models\WalletPayment;
use Bavix\Wallet\Models\Transaction;
use Bavix\Wallet\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::whereHas('roles', function($q)
        {
            $q->where('title','!=','vendor');
        })->get();
        return view('admin.user.user',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::get();
        return view('admin.user.create_user',compact('roles'));
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
            'name' => 'bail|required|max:255',
            'email_id' => 'bail|required|unique:users',
            'password' => 'bail|required|min:6',
            'phone' => 'bail|required|numeric|digits_between:6,12'
        ]);
        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;
        $data['is_verified'] = 1;
        $data['password'] = Hash::make($data['password']);
        $data['image'] = 'noimage.png';
        $user = User::create($data);
        $user->roles()->sync($request->input('roles', []));
        return redirect('admin/user')->with('msg','user added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $orders = Order::where('user_id',$user->id)->get();
        $pending_orders = Order::where([['user_id',$user->id],['order_status','PENDING']])->get();
        $approve_orders = Order::where([['user_id',$user->id],['order_status','APPROVE']])->get();
        $complete_orders = Order::where([['user_id',$user->id],['order_status','COMPLETE']])->get();
        return view('admin.user.show_user',compact('user','orders','approve_orders','pending_orders','complete_orders'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roles = Role::get();
        $user = User::find($id);
        return view('admin.user.edit_user',compact('user','roles'));
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
        $request->validate([
            'name' => 'bail|required|max:255',
            'phone' => 'bail|required|numeric|digits_between:6,12'
        ]);
        $data = $request->all();
        $user = User::find($id);
        if($data['password'] != null)
        {
            $request->validate([
                'password' => 'bail|min:6',
            ]);
            $data['password'] = Hash::make($data['password']);
        }
        else
            $data['password'] = $user->password;
        if($user->id != 1)
            $user->update($data);
        $user->roles()->sync($request->input('roles', []));
        return redirect('admin/user')->with('msg','User Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if($user->id != 1){
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
            return response(['success' => true]);
        }
    }

    public function change_status(Request $request)
    {
        $data = User::find($request->id);
        $data['status'] = $data->status == 1 ? 0 : 1;
        $data->save();
        return response(['success' => true]);
    }

    public function user_wallet(Request $request,$user_id)
    {
        $user = User::find($user_id);
        $user->deposit = Transaction::with('wallet')->where([['payable_id',$user_id],['type','deposit']])->sum('amount');
        $user->withdraw = Transaction::with('wallet')->where([['payable_id',$user_id],['type','withdraw']])->sum('amount');
        $currency = GeneralSetting::first()->currency_symbol;
        $transactions = Transaction::with('wallet')->where('payable_id',$user_id)->orderBy('id','DESC')->get();
        if(isset($request->date_range)){
            $date = explode(' - ',$request->date_range);
            $transactions = Transaction::with('wallet')->whereBetween('created_at', [$date[0], $date[1]])->where('payable_id',$user_id)->orderBy('id','DESC')->get();
        }
        foreach ($transactions as $transaction) {
            $transaction->payment_details = WalletPayment::where('transaction_id',$transaction->id)->first();
            $transaction->date = Carbon::parse($transaction->created_at);
        }
        return view('admin.user.user_wallet',compact('transactions','user','currency'));
    }

    public function add_wallet(Request $request)
    {
        $request->validate([
            'amount' => 'bail|required|numeric',
        ]);
        $data = $request->all();
        $user = User::find($data['user_id']);
        $deposit = $user->deposit($data['amount']);
        $transction = array();
        $transction['transaction_id'] = $deposit->id;
        $transction['payment_type'] = 'LOCAL';
        $transction['payment_token'] = $request->payment_token;
        $transction['added_by'] = 'admin';
        WalletPayment::create($transction);
        return redirect()->back();
    }
}
