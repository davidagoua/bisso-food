<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';

    protected $fillable = ['order_id','tax','vendor_id','user_id','payment_token','delivery_person_id','date','time','amount','payment_type','payment_status','vendor_discount','promocode_id','promocode_price','address_id','vendor_discount_id','vendor_discount_price','order_status','delivery_charge','order_start_time','order_end_time','delivery_type','admin_commission','vendor_amount','vendor_pending_amount'];

    protected $appends = ['vendor','user','orderItems','user_address'];

    public function getVendorAttribute()
    {
        return Vendor::find($this->vendor_id);
    }

    public function getUserAttribute()
    {
        return User::find($this->user_id);
    }

    public function getOrderItemsAttribute()
    {
        return OrderChild::where('order_id',$this->attributes['id'])->get();
    }

    public function getUserAddressAttribute()
    {
        return UserAddress::where('id',$this->attributes['address_id'])->first(['lat','lang','address']);
    }
}
