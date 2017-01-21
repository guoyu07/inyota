<?php

namespace Zank\Database\Seed;

use Zank\Interfaces\Database\Seed\SeederInterface;
use Symfony\Component\Console\Command\Command;

abstract class Seeder implements SeederInterface
{
    protected $command;

    abstract protected function handle();

    abstract protected function reset();

    public function run(bool $reset = false)
    {
        if ($reset) {
            $this->reset();
        }
        $this->handle();
    }

    protected function call(string $class)
    {
        $seeder->resolve($class)->run();

        if (isset($this->command)) {
            $this->command->getOutput()->writeln("<info>Seeded:</info> $class");
        }
    }

    protected function resolve(string $class)
    {
        $seeder = new $class;
        if (isset($this->command)) {
            $seeder->setCommand($this->command);
        }

        return $seeder;
    }

    public function setCommand(Command $command)
    {
        $this->command = $command;
    }
}
