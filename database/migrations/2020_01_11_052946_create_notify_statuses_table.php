<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotifyStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notify_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('schedule_id');
            $table->unsignedInteger('read_status')->default(0)->comment('0-unread,1-read');
            $table->tinyInteger('active')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('notify_statuses', function (Blueprint $table) {
            $table->foreign('schedule_id')->references('id')->on('schedules');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
        });
        Schema::dropIfExists('notify_statuses');
    }
}
