<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParsecLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('parsec_log', function (Blueprint $table) {
            $table->increments('id');
            $table->date('datetime_record');
            $table->string('user',  255);
            $table->boolean('action');
            $table->string('area',  255)->nullable()->default(null);
            $table->timestamps();
            $table->integer('processed')->default(0)->index();
            $table->datetime('processed_at')->default(null);
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
        Schema::dropIfExists('parsec_log');
    }
}
