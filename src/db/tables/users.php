<?php

use Illuminate\Database\Schema\Blueprint;

return function (Blueprint $table): void
{
    $table->increments('users_id');
    $table->string('phone');
    $table->string('username', 100);
    $table->string('password', 255);
    $table->string('hash', 100);
    $table->bigInteger('avatar')->nullable()->default(0);
    $table->smallInteger('age')->nullable()->default(0);
    $table->smallInteger('height')->nullable()->default(170);
    $table->smallInteger('kg')->nullable()->default(60);
    $table->enum('role', ['1', '0.5', '0'])->nullable()->default('0.5');
    $table->string('geohash', 100);
    $table->string('latitude', 100);
    $table->string('longitude', 100);
    $table->timestamps();
    $table->softDeletes();
    $table->unique('phone');
    $table->unique('username');
    $table->index('geohash');
    $table->index('latitude');
    $table->index('longitude');
    $table->index('age');
    $table->index('height');
    $table->index('kg');
    $table->index('role');
};