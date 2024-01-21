<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDinnerMenuComplex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('dinner_menu_complex', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date_menu_complex')->index();
            $table->string('meals_complex', 255);
            $table->float('price_meals_complex', 8, 2)->default(0.00)->unsigned();
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
        //
    }
}
