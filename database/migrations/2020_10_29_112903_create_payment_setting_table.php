<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // 'stripe_publish_key','stripe_secret_key','paypal_production','paypal_sendbox','razorpay_publish_key
    public function up()
    {
        Schema::create('payment_setting', function (Blueprint $table) {
            $table->id();
            $table->boolean('cod');
            $table->boolean('stripe');
            $table->boolean('razorpay');
            $table->boolean('paypal');
            $table->string('stripe_publish_key')->nullable();
            $table->string('stripe_secret_key')->nullable();
            $table->string('paypal_production')->nullable();
            $table->string('paypal_sendbox')->nullable();   
            $table->string('razorpay_publish_key')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_setting');
    }
}
