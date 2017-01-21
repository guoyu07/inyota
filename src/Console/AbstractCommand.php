<?php

namespace Zank\Console;

use Symfony\Component\Console\Command\Command;
use Zank\Interfaces\Console\CommandInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command implements CommandInterface
{
    protected $output;
    protected $input;

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
        $this->input = $input;
        $this->output = $output;
    }

    public function getOutput()
    {
        return $this->output;
    }
}
