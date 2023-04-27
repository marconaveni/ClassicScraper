<?php

namespace classic\app\config;

class config
{
    public static function getDotEnv(string $key)
    {
        static $envs;

        if (!$envs) {
            $envs = parse_ini_file('.env');
        }

        return empty($envs[$key]) ? null : $envs[$key];
    }
}
