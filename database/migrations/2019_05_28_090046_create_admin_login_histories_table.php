<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminLoginHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_login_histories', function (Blueprint $table) {
             $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('ip');
            $table->string('device_type')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('admin_login_histories', function (Blueprint $table) {
                    $table->foreign('user_id')->references('id')->on('admin_users')->onDelete('cascade');
                });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_login_histories', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('admin_login_histories');
    }
}
