<?php

use Illuminate\Database\Schema\Blueprint;

return function (Blueprint $table) {
    $table->increments('id');
    $table->integer('feed_id')->unsigned()->comment('feed id');
    $table->integer('attach_id')->unsigned()->comment('attach id');

    $table->timestamps();

    $table->index('feed_id');
    $table->index('attach_id');

    $table->engine = 'InnoDB';
};
