<?php

use Illuminate\Database\Schema\Blueprint;

return function (Blueprint $table) {
    $table->increments('id');
    $table->integer('feed_id')->unsigned()->comment('feed id');
    $table->integer('user_id')->unsigned()->comment('user id');

    $table->index('feed_id');
    $table->index('user_id');

    $table->timestamps();

    $table->engine = 'InnoDB';
};
