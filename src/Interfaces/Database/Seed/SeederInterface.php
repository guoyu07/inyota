<?php

namespace Zank\Interfaces\Database\Seed;

interface SeederInterface
{
    public function run(bool $reset = false);
}
