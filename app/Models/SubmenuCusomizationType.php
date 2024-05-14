<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmenuCusomizationType extends Model
{
    use HasFactory;

    protected $table = 'submenu_cutsomization_type';

    protected $fillable = ['name','vendor_id','custimazation_item','menu_id','submenu_id','type','min_item_selection','max_item_selection'];
}
