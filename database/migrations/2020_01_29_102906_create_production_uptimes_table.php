<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionUptimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_uptimes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('line');
            $table->unsignedInteger('type');
            $table->string('jan')->default(0);
            $table->string('feb')->default(0);
            $table->string('mar')->default(0);
            $table->string('apr')->default(0);
            $table->string('may')->default(0);
            $table->string('jun')->default(0);
            $table->string('jul')->default(0);
            $table->string('aug')->default(0);
            $table->string('sep')->default(0);
            $table->string('oct')->default(0);
            $table->string('nov')->default(0);
            $table->string('dec')->default(0);
            $table->unsignedInteger('year');         
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_uptimes');
    }
}
