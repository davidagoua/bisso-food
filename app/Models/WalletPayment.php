<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletPayment extends Model
{
    use HasFactory;

    protected $table = 'wallet_payment';

    protected $fillable = ['transaction_id','payment_type','payment_token','added_by'];
}
