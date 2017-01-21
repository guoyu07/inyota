<?php

namespace Zank\Console\Command;

use DatabaseSeeder;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Zank\Console\AbstractCommand as Command;
use Zank\Traits\InitDatabaseToConsole;

class SeederCommand extends Command
{
    use InitDatabaseToConsole;

    protected function configure()
    {
        $this->setName('db:seed')
             ->addArgument('seed', InputArgument::IS_ARRAY, '输入需要运行的数据名称', [])
             ->addOption('reset', 'r', InputOption::VALUE_NONE, '是否删除数据表数据？')
             ->setDescription('导入数据表数据')
             ->setHelp('导入数据表数据，设置table，导入指定表，如果不设置默认全部，设置reset则会删除已有数据.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $consoleStyle = new SymfonyStyle($input, $output);
        $consoleStyle->title('数据填充');

        $seedClasses = $input->getArgument('seed');
        $reset = $input->getOption('reset');

        if (empty($seedClasses)) {

            $seeder = new DatabaseSeeder();
            $seeder->setCommand($this);
            $seeder->run($reset);

            return ;
        }

        foreach ($seedClasses as $class) {
            $consoleStyle->writeln("<info>Seeded:</info> $class");
            $seeder = new $class();
            $seeder->setCommand($this);
            $seeder->run($reset);
        }
    }
}
