<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditRoomBookingsAddAdditionalFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('room_bookings', function (Blueprint $table) {
            $table->boolean('notebook_own')->default(0);
            $table->boolean('notebook_ukot')->default(0);
            $table->boolean('info_internet')->default(0);
            $table->boolean('info_kodeks')->default(0);
            $table->boolean('software_skype')->default(0);
            $table->boolean('software_skype_for_business')->default(0);
            $table->boolean('type_meeting_webinar')->default(0);
            $table->boolean('type_meeting_other')->default(0);
            $table->string('notes', 255)->nullable();
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
