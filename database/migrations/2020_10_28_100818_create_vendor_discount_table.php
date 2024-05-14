<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorDiscountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_discount', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->integer('vendor_id');
            $table->string('type');
            $table->string('min_item_amount');
            $table->string('max_discount_amount');
            $table->string('start_end_time');
            $table->text('description');
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
        Schema::dropIfExists('vendor_discount');
    }
}
