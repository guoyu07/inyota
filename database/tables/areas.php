<?php

use Illuminate\Database\Schema\Blueprint;

return function (Blueprint $table) {
    $table->increments('id');
    $table->string('name', 250);
    $table->integer('pid')->nullable()->default(0);
    $table->string('extends')->nuable()->default('')->comment('扩展内容');

    $table->timestamps();
    $table->softDeletes();

    $table->index('name');
    $table->index('pid');

    $table->engine = 'InnoDB';
};
