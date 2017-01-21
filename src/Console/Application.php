<?php

namespace Zank\Console;

use Composer\Console\Application as BaseApplication;
use Composer\Command as ComposerCommand;
use Symfony\Component\Console\Command as SymfonyCommand;

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
