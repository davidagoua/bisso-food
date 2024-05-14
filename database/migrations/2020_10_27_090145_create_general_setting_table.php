<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_setting', function (Blueprint $table) {
            $table->id();
            $table->string('start_time',100)->nullable();
            $table->string('end_time',100)->nullable();
            $table->boolean('business_availability')->nullable();
            $table->integer('isItemTax')->nullable();
            $table->string('item_tax')->nullable();
            $table->string('tax_type')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('recommanded_menu')->nullable();
            $table->boolean('isPickup')->nullable();
            $table->boolean('isSameDayDelivery')->nullable();
            $table->string('vendor_distance');
            $table->string('payment_type')->nullable();
            $table->string('items_count')->nullable();
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
        Schema::dropIfExists('general_setting');
    }
}
