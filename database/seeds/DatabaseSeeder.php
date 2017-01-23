<?php

use InYota\Database\Seed\Seeder;

class DatabaseSeeder extends Seeder
{
    protected function handle(bool $reset)
    {
        $this->call(AreaTableSeeder::class, $reset);
        $this->call(UserTableSeeder::class, $reset);
    }

    protected function reset()
    {
    }
}
