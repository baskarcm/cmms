<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_type')->unsigned();
            $table->string('name');
            $table->string('code');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('password');
            $table->string('token')->nullable();
            $table->unsignedInteger('gender')->default(0);
            $table->text('profile_pic')->nullable();
            $table->tinyInteger('active')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('blocked_at')->nullable();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('user_type')->references('id')->on('user_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['user_type']);
        });
        Schema::dropIfExists('users');
    }
}
