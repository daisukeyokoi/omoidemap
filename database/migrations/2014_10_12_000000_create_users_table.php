<?php

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
            $table->string('oauth_id');
            $table->string('nickname');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('image');
            $table->integer('birth_year');
            $table->integer('birth_month');
            $table->integer('birth_day');
            $table->string('sex');
            $table->integer('birthplace');
            $table->string('confirmation_token')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->integer('identification');
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
