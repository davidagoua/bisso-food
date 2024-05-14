<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderChild extends Model
{
    use HasFactory;

    protected $table = 'order_child';

    protected $fillable = ['order_id','item','price','qty','custimization'];

    protected $appends = ['itemName','custimization'];

    public function getItemNameAttribute()
    {
        if($this->attributes['item'] != null)
        {
            return Submenu::where('id',$this->attributes['item'])->first()->name;
        }
        else
        {
            return null;
        }
    }

    // public function getPriceAttribute()
    // {
    //     return number_format(intval($this->attributes['price']),2);
    //     // if($this->attributes['item'] != null)
    //     // {
    //     //     return Submenu::where('id',$this->attributes['item'])->first()->name;
    //     // }
    //     // else
    //     // {
    //     //     return null;
    //     // }
    // }

    public function getCustimizationAttribute()
    {
        if($this->attributes['custimization'] != null)
        {
            $array = json_decode($this->attributes['custimization']);
            if(!is_array($array))
            {
                $array = json_decode($array);
                if(!is_array($array))
                {
                    $array = json_decode($array);
                }
            }
            return $array;
        }
        else
        {
            return null;
        }
    }
}
