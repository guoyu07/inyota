<?php

use Illuminate\Database\Schema\Blueprint;

return function (Blueprint $table) {
    $table->increments('id');
    $table->integer('user_id');
    $table->integer('attach_id');
    $table->index('user_id');
    $table->index('attach_id');
    $table->timestamps();
};
