<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // 'order_id','vendor_id','user_id','delivery_person_id','date','time','amount','item','payment_type','payment_status','coupen_id','coupen_price','address_id','delivery_charge'
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('vendor_id');
            $table->integer('user_id');
            $table->integer('delivery_person_id');
            $table->date('date');
            $table->time('time');
            $table->string('payment_type');
            $table->string('payment_status');
            $table->integer('amount');
            $table->string('delivery_type');
            $table->integer('item');
            $table->integer('coupen_id');
            $table->integer('coupen_price');
            $table->integer('address_id');
            $table->integer('delivery_charge');
            $table->string('order_status');
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
        Schema::dropIfExists('order');
    }
}
