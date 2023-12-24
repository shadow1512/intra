<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDinnerMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('dinner_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date_menu');
            $table->string('meals', 255);
            $table->float('price_meals', 8, 2)->default(0.00)->unsigned();
            $table->string('type_meals', 16)->nullable();
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
    }
}
