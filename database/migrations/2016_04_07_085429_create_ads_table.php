<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('page');
            $table->string('ads_id');
            $table->string('city');
            $table->string('lang');
            $table->string('img');
            $table->string('url');
            $table->text('message');
            $table->string('title');
            $table->integer('price');
            $table->bigInteger('profile');
            $table->string('profile_name');
            $table->integer('user_id');
            $table->timestamp('published');
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
        Schema::drop('ads');
    }
}
