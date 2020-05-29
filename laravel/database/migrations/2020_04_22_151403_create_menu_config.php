<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('menu_config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->boolean('container')->default(false);
            $table->integer('parent_id')->nullable()->default(null);
            $table->string('link', 255)->nullable()->default(null);
            $table->string('menu', 16);
            $table->integer('sort')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('menu_config');//
    }
}
