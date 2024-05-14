<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settle extends Model
{
    use HasFactory;

    protected $table = 'settlements';

    protected $fillable = ['vendor_id','order_id','payment','payment_token','payment_type','admin_earning','vendor_earning','driver_earning','driver_id','driver_status','vendor_status','driver_payment_token','driver_payment_type'];
}
