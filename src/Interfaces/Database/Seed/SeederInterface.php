<?php

namespace Zank\Interfaces\Database\Seed;
use Symfony\Component\Console\Command\Command;

interface SeederInterface
{
    public function run(bool $reset = false);
}
