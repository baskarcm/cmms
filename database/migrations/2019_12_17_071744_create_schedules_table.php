<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_type');
            $table->unsignedInteger('user_id')->nullable()->comment('technician');
            $table->unsignedInteger('engineer_id')->nullable();
            $table->unsignedInteger('manager_id')->nullable();
            $table->timestamp('schedule_date');
            $table->string('title')->nullable();
            $table->unsignedInteger('module_type')->comment('1-pm,2-breakdown');
            $table->unsignedInteger('schedule_status')->default(0)->comment('0-schedule,1-pending,2-complete');
            $table->tinyInteger('engineer_status')->default(0)->comment('0-pending,1-reject,2-approval');
            $table->string('engineer_comment')->nullable();
            $table->string('ref_no');
            $table->timestamp('failure')->nullable();
            $table->timestamp('reporting')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->foreign('product_type')->references('id')->on('product_types');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['product_type']);
        });
        Schema::dropIfExists('schedules');
    }
}
