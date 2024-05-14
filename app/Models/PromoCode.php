<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;

    protected $table = 'promo_code';

    protected $fillable = ['name','promo_code','display_customer_app','vendor_id', 'customer_id','isFlat','flatDiscount','discountType','discount','max_disc_amount','min_order_amount','max_user','max_count','max_order','start_end_date','coupen_type','description','display_text','image','status'];

    protected $appends = ['image'];

    public function getImageAttribute()
    {
        return url('images/upload') . '/'.$this->attributes['image'];
    }

    public function getDiscountAttribute()
    {
        if($this->attributes['discount'] == null)
        {
            return 0;
        }
        return $this->attributes['discount'];
    }

    protected $casts = [
        'flatDiscount' => 'float',
        'discount' => 'float',
    ];
}
