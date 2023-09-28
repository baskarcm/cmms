<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePmDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pm_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pm_id');
            $table->unsignedInteger('inspec_point');
            $table->unsignedInteger('inspec_item');
            $table->unsignedInteger('product_judge'); 
            $table->text('status')->nullable();                                      
            $table->text('defect_item')->nullable();
            $table->text('defect_image')->nullable();
            $table->text('action')->nullable();
            $table->text('action_image')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->timestamps();
        });

        Schema::table('pm_details', function (Blueprint $table) {
            $table->foreign('pm_id')->references('id')->on('pm_modules');
            $table->foreign('inspec_point')->references('id')->on('inspection_points');
            $table->foreign('inspec_item')->references('id')->on('inspection_iteams');
            $table->foreign('product_judge')->references('id')->on('product_judges');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pm_details', function (Blueprint $table) {
            $table->dropForeign(['pm_id']);
            $table->dropForeign(['inspec_point']);
            $table->dropForeign(['inspec_item']);
            $table->dropForeign(['product_judge']);
        });
        Schema::dropIfExists('pm_details');
    }
}
