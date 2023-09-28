<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('schedule_id');
            $table->unsignedInteger('user_id')->comment('technician,engineer,manager');
            $table->unsignedInteger('status')->default(0);
            $table->tinyInteger('active')->default(1);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->foreign('schedule_id')->references('id')->on('schedules');
            $table->foreign('user_id')->references('id')->on('users');
       });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('notifications');
    }
}
