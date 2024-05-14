<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $table = 'refaund';

    protected $fillable = ['order_id','user_id','refund_reason','refund_status','payment_type','payment_token'];

    protected $appends = ['order','user'];

    public function getOrderAttribute()
    {
        return Order::where('id',$this->attributes['order_id'])->first(['id','order_id','amount'])->makeHidden(['vendor','user','orderItems','user_address']);
    }

    public function getUserAttribute()
    {
        return User::where('id',$this->attributes['user_id'])->first(['id','name'])->makeHidden(['image']);
    }
}
