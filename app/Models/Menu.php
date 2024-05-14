<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';

    protected $fillable = ['name','menu_category_id','vendor_id','image','status'];

    protected $appends = ['image'];

    public function getImageAttribute()
    {
        return url('images/upload') . '/'.$this->attributes['image'];
    }
}
