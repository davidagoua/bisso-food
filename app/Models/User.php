<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Wallet;

class User extends Authenticatable implements Wallet
{
    use HasApiTokens, HasFactory, Notifiable,HasWallet;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email_id',
        'password',
        'phone',
        'status',
        'image',
        'phone_code',
        'is_verified',
        'faviroute',
        'vendor_id',
        'ifsc_code',
        'account_name',
        'language',
        'micr_code',
        'account_number',
        'provider_type',
        'provider_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['image'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function getImageAttribute()
    {
        return url('images/upload') . '/'.$this->attributes['image'];
    }
}
