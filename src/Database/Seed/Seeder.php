<?php

namespace InYota\Database\Seed;

use InYota\Interfaces\Database\Seed\SeederInterface;
use Symfony\Component\Console\Command\Command;

abstract class Seeder implements SeederInterface
{
    protected $command;

    abstract protected function handle(bool $reset);

    abstract protected function reset();

    public function run(bool $reset = false)
    {
        if ($reset) {
            $this->reset();
        }
        $this->handle($reset);
    }

    protected function call(string $class, bool $reset = false)
    {
        if (isset($this->command)) {
            $this->command->getOutput()->writeln("<info>Seeded:</info> $class");
        }
        $this->resolve($class)->run($reset);
    }

    protected function resolve(string $class)
    {
        $seeder = new $class();
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
