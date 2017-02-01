<?php

namespace InYota\Interfaces\Database\Seed;

interface SeederInterface
{
    public function run(bool $reset = false);
}
