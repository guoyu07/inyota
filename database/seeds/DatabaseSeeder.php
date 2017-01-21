<?php

use Zank\Database\Seed\Seeder;

class DatabaseSeeder extends Seeder
{
    protected function handle(bool $reset)
    {
        $this->call(AreaTableSeeder::class, $reset);
    }

    protected function reset()
    {
    }
}
