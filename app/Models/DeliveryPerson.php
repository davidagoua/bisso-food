<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class DeliveryPerson extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'delivery_person';

    protected $fillable = ['image','vendor_id','device_token','is_online','is_verified','otp','phone_code','first_name','last_name','delivery_zone_id','email_id','contact','full_address','password','vehicle_type','vehicle_number','licence_number','national_identity','licence_doc','lat','lang','status'];

    protected $appends = ['image','deliveryzone'];

    public function getImageAttribute()
    {
        return url('images/upload') . '/'.$this->attributes['image'];
    }

    public function getDeliveryZoneAttribute()
    {
        if (isset($this->attributes['delivery_zone_id']))
        {
            $driver = DeliveryZone::find($this->attributes['delivery_zone_id']);
            if($driver)
            {
                return $driver->name;
            }
            else
            {
                return null;
            }
        }
        else
        {
            return null;
        }
    }
}
