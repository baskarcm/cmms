<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_type');
            $table->unsignedInteger('level');
            $table->unsignedInteger('inspec_point')->default(0);
            $table->unsignedInteger('inspec_iteam')->default(0);
            $table->tinyInteger('active')->default(1);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->softDeletes();
            $table->timestamps();

        });
        Schema::table('product_forms', function (Blueprint $table) {
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
        Schema::table('product_forms', function (Blueprint $table) {
            $table->dropForeign(['product_type']);
        });
        Schema::dropIfExists('product_forms');
    }
}
