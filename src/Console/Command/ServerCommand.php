<?php

namespace Zank\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\ProcessUtils;
use Zank\Console\Command;

class ServerCommand extends Command
{
    protected function configure()
    {
        $this->setName('server')
             ->addOption('host', null, InputOption::VALUE_OPTIONAL, 'The host address to serve the application on.', '127.0.0.1')
             ->addOption('port', null, InputOption::VALUE_OPTIONAL, 'The port to serve the application on.', 8080)
             ->setDescription('Serve the application on the PHP development server');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        chdir(public_path());

        $host = $input->getOption('host');
        $port = $input->getOption('port');
        $base = ProcessUtils::escapeArgument(public_path());
        $binary = ProcessUtils::escapeArgument((new PhpExecutableFinder())->find(false));

        $output->writeln("<info>Zank development server started on</info> http://{$host}:{$port}/");

        passthru("{$binary} -S {$host}:{$port} {$base}/index.php");
    }
}
