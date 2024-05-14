<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderSettingItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // 'min_order_value','order_assign_manually','orderRefresh','order_dashboard_default_time','vendor_order_max_time','driver_order_max_time','delivery_charge_type','min_value','max_value','charges',
    public function up()
    {
        Schema::create('order_setting_item', function (Blueprint $table) {
            $table->id();
            $table->string('min_order_value');
            $table->string('order_assign_manually');
            $table->string('orderRefresh');
            $table->string('order_dashboard_default_time');
            $table->string('vendor_order_max_time');
            $table->string('driver_order_max_time');
            $table->string('delivery_charge_type');
            $table->string('min_value');
            $table->string('max_value');
            $table->string('charges');
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
        Schema::dropIfExists('order_setting_item');
    }
}
