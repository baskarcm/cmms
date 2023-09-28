<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspectionIteamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspection_iteams', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id')->default(0);
            $table->unsignedInteger('inspec_point')->default(0);
            $table->string('name');
            $table->tinyInteger('active')->default(1);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('inspection_iteams', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('inspec_point')->references('id')->on('inspection_points');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inspection_iteams', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['inspec_point']);
        });
        Schema::dropIfExists('inspection_iteams');
    }
}
