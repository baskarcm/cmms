<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePmModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pm_modules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_type');
            $table->unsignedInteger('user_id')->comment('technician');
            $table->unsignedInteger('engineer_id');
            $table->unsignedInteger('manager_id');
            $table->unsignedInteger('schedule_id');
            $table->tinyInteger('engineer_status')->default(0)->comment('0-pending,1-reject,2-approval');
            $table->timestamp('date');
            $table->string('root_cause');
            $table->tinyInteger('active')->default(1);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('pm_modules', function (Blueprint $table) {
            $table->foreign('product_type')->references('id')->on('product_types');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('manager_id')->references('id')->on('users');
            $table->foreign('engineer_id')->references('id')->on('users');
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
        Schema::table('pm_modules', function (Blueprint $table) {
            $table->dropForeign(['product_type']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['engineer_id']);
            $table->dropForeign(['manager_id']);
            $table->dropForeign(['schedule_id']);
        });
        Schema::dropIfExists('pm_modules');
    }
}
