<?php

namespace InYota;

use Slim\App as SlimApp;

/**
 * InYota application.
 *
 * @author Seven Du <lovevipdsw@outlook.com>
 **/
class Application
{
    protected static $application;

    private static $client;

    private static $requireFiles = [];

    public function __construct($container)
    {
        if (!self::$application instanceof SlimApp) {
            self::$application = new SlimApp($container);
        }
    }

    public function run(array $requireFiles = [])
    {
        self::requires($requireFiles);

        $slient = strtolower(PHP_SAPI) === 'cli';
        $client = self::$application->run($slient);
        self::$client = $client;

        // CLI.
        if ($slient === true) {
            $client = new Console\Application();
            $client->run();
        }

        return $client;
    }

    public static function getClient()
    {
        return self::$client;
    }

    public static function requires(array $requireFiles = [], bool $notOne = false)
    {
        $rets = [];

        foreach ($requireFiles as $key => $file) {
            if (!isset(self::$requireFiles[$file]) || $notOne === true) {
                self::$requireFiles[$file] = $rets[$key] = require $file;

                continue;
            }

            $rets[$key] = self::$requireFiles[$file];
        }

        return $rets;
    }

    public static function __callStatic($funcname, $arguments)
    {
        if (self::$application instanceof SlimApp) {
            return call_user_func_array([self::$application, $funcname], $arguments);
        }

        throw new \Exception(sprintf('Error: Not new the %s class.', __CLASS__));
    }
} // END class Application
