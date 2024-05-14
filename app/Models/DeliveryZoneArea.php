<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryZoneArea extends Model
{
    use HasFactory;

    protected $table = 'delivery_zone_area';

    protected $fillable = ['name','vendor_id','radius','lat','lang','location','delivery_zone_id'];
}
