<?php

namespace Zank\Console;

use Composer\Command as ComposerCommand;
use Composer\Console\Application as BaseApplication;
use Composer\Factory;
use Composer\XdebugHandler;
use Symfony\Component\Console\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends BaseApplication
{
    public static $name = 'Zank Command Tool';
    public static $version = '1.0.0';

    public function __construct()
    {
        parent::__construct();

        $this->setName(static::$name);
        $this->setVersion(static::$version);

        $this->addCommands([
            new Command\TableImportCommand(),
            new Command\TableDeleteCommand(),
            new Command\SeederCommand(),
        ]);

        $this->setDefaultCommand('list');
    }

    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        // Create output for XdebugHandler and Application
        if ($output === null) {
            $output = Factory::createOutput();
        }

        $this->runXdebugHandler($output);

        if (function_exists('ini_set')) {
            @ini_set('display_errors', 1);

            $memoryInBytes = function ($value) {
                $unit = strtolower(substr($value, -1, 1));
                $value = (int) $value;
                switch ($unit) {
                    case 'g':
                        $value *= 1024;
                        // no break (cumulative multiplier)
                    case 'm':
                        $value *= 1024;
                        // no break (cumulative multiplier)
                    case 'k':
                        $value *= 1024;
                }

                return $value;
            };

            $memoryLimit = trim(ini_get('memory_limit'));
            // Increase memory_limit if it is lower than 1.5GB
            if ($memoryLimit != -1 && $memoryInBytes($memoryLimit) < 1024 * 1024 * 1536) {
                @ini_set('memory_limit', '1536M');
            }
            unset($memoryInBytes, $memoryLimit);
        }

        return parent::run($input, $output);
    }

    protected function runXdebugHandler(OutputInterface $output)
    {
        $xdebug = new XdebugHandler($output);
        $xdebug->check();
        unset($xdebug);
    }

    public function getHelp()
    {
        return $this->getLongVersion();
    }

    public function getLongVersion()
    {
        if ('UNKNOWN' !== $this->getName()) {
            if ('UNKNOWN' !== $this->getVersion()) {
                return sprintf('<info>%s</info> version <comment>%s</comment>', $this->getName(), $this->getVersion());
            }

            return sprintf('<info>%s</info>', $this->getName());
        }

        return '<info>Console Tool</info>';
    }

    protected function getDefaultCommands()
    {
        return [
            // Syfony commands.
            new SymfonyCommand\HelpCommand(),
            new SymfonyCommand\ListCommand(),

            // Composer commands.
            new ComposerCommand\DumpAutoloadCommand(),
        ];
    }
}
