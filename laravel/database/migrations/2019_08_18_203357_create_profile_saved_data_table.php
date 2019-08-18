<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileSavedDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('profiles_saved_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ps_id')->index();
            $table->string('field_name', 32);
            $table->string('old_value', 255)->nullable();
            $table->string('new_value', 255)->nullable();
            $table->smallInteger('status')->default(1);
            $table->string('reason', 255)->nullable();
            $table->integer('creator_id');
            $table->timestamps();
            $table->softDeletes('deleted_at');
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
        Schema::dropIfExists('profiles_saved_data');//
    }
}
