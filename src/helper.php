<?php

use Symfony\Component\Yaml\Yaml;

if (!function_exists('env')) {
    /**
     * env YAML配置获取
     *
     * @param string $key 键名
     * @param mixed $default 默认值
     * @return mixed
     * @author Seven Du <lovevipdsw@outlook.com>
     * @homepage http://medz.cn
     */
    function env(string $key, $default = null)
    {
        static $yaml;

        if (!is_array($yaml)) {
            $env = dirname(__DIR__).'/.env';

            if (file_exists($env)) {
                $env = file_get_contents($env);
            } else {
                $env = '';
            }

            $yaml = Yaml::parse($env);
        }

        if (!isset($yaml[$key])) {
            return $default;
        }

        return $yaml[$key];
    }
}

if (!function_exists('app')) {
    /**
     * get slim application
     *
     * @return Slim\App
     * @author Seven Du <lovevipdsw@outlook.com>
     * @homepage http://medz.cn
     */
    function app()
    {
        return \Zank\App::getApplication();
    }
}
