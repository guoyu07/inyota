<?php

use InYota\Database\Seed\Seeder;
use InYota\Model\User;

class UserTableSeeder extends Seeder
{
    protected function handle(bool $reset)
    {
        $user = new User();
        $user->phone = '18781993582';
        $user->username = 'seven';
        $user->hash = str_random(64);
        $user->password = md5($user->hash.'123456');
        $user->save();
    }

    protected function reset()
    {
        User::truncate();
    }
}
