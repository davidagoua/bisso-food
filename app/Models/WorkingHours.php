<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingHours extends Model
{
    use HasFactory;

    protected $table = 'working_hours';

    protected $fillable = ['vendor_id','day_index','type','period_list','status'];
}
