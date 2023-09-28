<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBreakdownProblemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('breakdown_problem_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('breakdown_id');
            $table->string('problem')->nullable();
            $table->string('pdown')->nullable();            
            $table->string('route_cause')->nullable();            
            $table->string('mr_status')->nullable(); 
            $table->tinyInteger('active')->default(1);           
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('breakdown_problem_details', function (Blueprint $table) {
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
        Schema::table('breakdown_problem_details', function (Blueprint $table) {
            $table->dropForeign(['breakdown_id']);
        });
        Schema::dropIfExists('breakdown_problem_details');
    }
}
