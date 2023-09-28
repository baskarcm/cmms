<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBreakdownDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('breakdown_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('breakdown_id');
            $table->string('problem')->nullable();
            $table->text('problem_image')->nullable();
            $table->string('action')->nullable();
            $table->text('action_image')->nullable();
            $table->string('prevention')->nullable();
            $table->text('prevention_image')->nullable();
            $table->tinyInteger('type')->default(0)->comment('1-problem,2-action,3-prevention');
            $table->tinyInteger('active')->default(1);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('breakdown_details', function (Blueprint $table) {
            $table->foreign('breakdown_id')->references('id')->on('breakdown_modules');
       });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('breakdown_details', function (Blueprint $table) {
            $table->dropForeign(['breakdown_id']);
        });
        Schema::dropIfExists('breakdown_details');
    }
}
