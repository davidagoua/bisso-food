<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    use HasFactory;

    protected $table = 'payment_setting';

    protected $fillable = [
        'cod', 'stripe', 'razorpay', 'paypal', 'flutterwave', 'wallet', 'paypal_client_id', 'paypal_secret_key',
        'stripe_publish_key', 'public_key', 'stripe_secret_key', 'paypal_production', 'paypal_sendbox', 'razorpay_publish_key', 'is_debbug_mode', 'encryption_key'
    ];
}
