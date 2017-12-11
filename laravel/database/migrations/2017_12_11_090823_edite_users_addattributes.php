<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditeUsersAddattributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 3)->nullable();
            $table->string('mobile_phone', 11)->nullable();
            $table->string('room', 3)->nullable();
            $table->softDeletes();
            $table->date('birthday')->nullable();
            $table->date('workstart')->nullable();
            $table->date('workend')->nullable();
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
