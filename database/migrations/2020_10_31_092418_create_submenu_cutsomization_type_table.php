<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmenuCutsomizationTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ,'vendor_id','menu_id','type','min_item_selection','max_item_selection'
    public function up()
    {
        Schema::create('submenu_cutsomization_type', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('vendor_id');
            $table->integer('submenu_id');
            $table->integer('menu_id');
            $table->string('type');
            $table->integer('min_item_selection');
            $table->integer('max_item_selection');
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
        Schema::dropIfExists('submenu_cutsomization_type');
    }
}
