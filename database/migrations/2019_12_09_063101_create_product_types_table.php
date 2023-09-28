<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('doc_no');
            $table->string('code')->nullable();
            $table->string('location')->nullable();
            $table->string('station')->nullable();
            $table->string('pic_team')->nullable();
            $table->string('barcode')->nullable();
            $table->string('barcode_file')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->softDeletes();
            $table->timestamps();

        });
        Schema::table('product_types', function (Blueprint $table) {
                $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_types', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });
        Schema::dropIfExists('product_types');
    }
}
