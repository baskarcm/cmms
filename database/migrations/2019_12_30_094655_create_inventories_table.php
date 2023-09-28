<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_type');
            $table->unsignedInteger('schedule_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('breakdown_id');
            $table->timestamp('date');
            $table->string('title')->nullable();
            $table->string('name')->nullable();
            $table->text('file')->nullable();
            $table->tinyInteger('active')->default(0);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->foreign('product_type')->references('id')->on('product_types');
            $table->foreign('breakdown_id')->references('id')->on('breakdown_modules');
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
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropForeign(['product_type']);
            $table->dropForeign(['breakdown_id']);
            $table->dropForeign(['schedule_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('inventories');
    }
}
