<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditProfilesSavedDropColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('profiles_saved', function (Blueprint $table) {
            $table->dropColumn(['name', 'fname', 'lname',   'mname',    'email',    'email_secondary',  'phone',    'city_phone',   'mobile_phone', 'avatar',
                'room', 'birthday', 'workstart',    'workend',  'address',  'position_desc',    'dep_id',   'work_title',    'numpark', 'approved']);
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
