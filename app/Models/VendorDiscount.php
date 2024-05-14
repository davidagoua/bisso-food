<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorDiscount extends Model
{
    use HasFactory;

    protected $table = 'vendor_discount';

    protected $fillable = ['image','vendor_id','type','discount','min_item_amount','max_discount_amount','start_end_date','description'];
}
