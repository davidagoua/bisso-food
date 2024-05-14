<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'review';

    protected $fillable = ['rate','comment','image','contact','order_id','user_id','vendor_id'];

    protected $appends = ['user','order'];

    public function getUserAttribute()
    {
        return User::where('id',$this->attributes['user_id'])->first(['name','image']);
    }

    public function getOrderAttribute()
    {
        return Order::find($this->attributes['order_id'])->order_id;
    }
}
