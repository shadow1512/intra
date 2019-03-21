<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSavedProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('profiles_saved', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('name', 255)->nullable();
            $table->string('fname', 255);
            $table->string('lname', 255);
            $table->string('mname', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('email_secondary', 255)->nullable();
            $table->string('phone', 24)->nullable();
            $table->string('city_phone', 24)->nullable();
            $table->string('mobile_phone', 24)->nullable();
            $table->string('avatar', 255)->nullable();
            $table->string('room', 24)->nullable();
            $table->date('birthday')->nullable();
            $table->date('workstart')->nullable();
            $table->date('workend')->nullable();
            $table->string('address', 255)->nullable();
            $table->string('position_desc', 255)->nullable();
            $table->integer('dep_id')->nullable();
            $table->string('work_title', 255)->nullable();
            $table->string('numpark', 255)->nullable();

            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('profiles_saved');
    }
}
