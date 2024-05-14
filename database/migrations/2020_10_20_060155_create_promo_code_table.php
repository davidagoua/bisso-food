<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_code', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('promo_code');
            $table->string('image');
            $table->boolean('display_customer_app');
            $table->text('vendor_id')->nullable();
            $table->text('customer_id')->nullable();
            $table->boolean('isFlat');
            $table->string('flatDiscount')->nullable();
            $table->string('discountType',100);
            $table->string('max_disc_amount')->nullable();
            $table->string('min_order_amount');
            $table->integer('max_count');
            $table->string('max_order');
            $table->string('max_user');
            $table->text('start_end_date');
            $table->string('coupen_type');
            $table->text('description');
            $table->text('display_text')->nullable();
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
        Schema::dropIfExists('promo_code');
    }
}
