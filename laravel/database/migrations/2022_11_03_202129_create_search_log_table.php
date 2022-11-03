<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearchLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('search_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('term', 255);
            $table->integer('total_res')->default(0)->unsigned();
            $table->string('section_results', 255)->nullable();
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
        Schema::dropIfExists('search_logs');
    }
}
