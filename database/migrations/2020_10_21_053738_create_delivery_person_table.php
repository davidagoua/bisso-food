<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryPersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ['first_name','last_name','email_id','contact','full_address','vehicle_type','vehicle_number','licence_number','national_identity','vehical_doc']
    public function up()
    {
        Schema::create('delivery_person', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('image');
            $table->string('email_id');
            $table->string('contact');
            $table->text('full_address');
            $table->text('vehicle_type');
            $table->string('vehicle_number');
            $table->string('licence_number');
            $table->string('national_identity');
            $table->string('licence_doc');
            $table->integer('delivery_zone_id');
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
        Schema::dropIfExists('delivery_person');
    }
}
