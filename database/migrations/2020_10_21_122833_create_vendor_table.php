<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ['name','email','password','contact','cuisine_id','address','lat','lang','min_order_amount','for_two_person','avg_delivery_time','license_number','admin_comission_type','admin_commision_value','vendor_type','time_slots','tax','delivery_type_timeSlots','payment_option'];
        Schema::create('vendor', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('contact');
            $table->string('cuisine_id');
            $table->string('address');
            $table->string('lat');
            $table->string('lang');
            $table->string('min_order_amount');
            $table->string('for_two_person');
            $table->string('avg_delivery_time');
            $table->string('license_number');
            $table->string('admin_comission_type');
            $table->string('admin_commision_value');
            $table->string('vendor_type');
            $table->string('time_slots');
            $table->string('tax')->nullable();
            $table->string('delivery_type_timeSlots');
            $table->string('payment_option');
            $table->boolean('status');
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
        Schema::dropIfExists('vendor');
    }
}
