<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    // ['vendor_id','menu_id','name','image','price','description','type','qty_reset','selling_timeslot','status'];
    public function up()
    {
        Schema::create('submenu', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id');
            $table->integer('menu_id');
            $table->string('name');
            $table->string('image');
            $table->string('price',100);
            $table->text('description');
            $table->string('type');
            $table->string('qty_reset');
            $table->boolean('selling_timeslot');
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
        Schema::dropIfExists('submenu');
    }
}
