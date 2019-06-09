<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechnicalRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('technical_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type_request');
            $table->timestamps();
            $table->softDeletes('deleted_at');
            $table->string('room')->nullable();
            $table->string('fio')->nullable();
            $table->string('printer')->nullable();
            $table->string('dep')->nullable();
            $table->string('user_comment')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('redmine_link')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('technical_requests');//
    }
}
