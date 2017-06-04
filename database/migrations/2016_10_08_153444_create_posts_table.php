<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();

            // 写真を撮ったときの年代
            $table->integer('age');

            // 写真をとった時の感情
            $table->integer('feeling');

            // 写真を撮った時のエピソード
            $table->text('episode');

            // 写真をとった場所の住所
            $table->string('address');

            // 緯度
            $table->double('lat');

            // 経度
            $table->double('lng');

            // イベントID
            $table->integer('event_id');

            $table->timestamps();

            // userが消えた時に一緒に削除
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('posts');
    }
}
