<?php

namespace InYota\Console;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use InYota\Interfaces\Console\CommandInterface;

abstract class Command extends BaseCommand implements CommandInterface
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
