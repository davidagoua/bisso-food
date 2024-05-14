<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submenu extends Model
{
    use HasFactory;

    protected $table = 'submenu';

    protected $fillable = ['item_reset_value','vendor_id','menu_id','is_excel','name','image','price','description','type','qty_reset','status','availabel_item'];

    protected $appends = ['image'];

    public function getImageAttribute()
    {
        return url('images/upload') . '/'.$this->attributes['image'];
    }
}
