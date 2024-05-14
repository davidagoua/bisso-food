<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmenuCutsomizationItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // 'cutsomization_type_id','name','price','default','status'
    public function up()
    {
        Schema::create('submenu_cutsomization_item', function (Blueprint $table) {
            $table->id();
            $table->integer('cutsomization_type_id');
            $table->string('name');
            $table->integer('price');
            $table->boolean('default');
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
        Schema::dropIfExists('submenu_cutsomization_item');
    }
}
