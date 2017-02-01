<?php

use Illuminate\Database\Schema\Blueprint;

return function (Blueprint $table) {
    $table->increments('id');
    $table->integer('user_id')->unsigned()->comment('Create user id');
    $table->text('content')->comment('content');
    $table->string('geohash', 100)->nullable()->default(null);

    $table->timestamps();
    $table->softDeletes();

    $table->index('user_id');
    $table->index('geohash');

    $table->engine = 'InnoDB';
};
