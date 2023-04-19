<?php 


function getDotEnv(string $key)
{
     static $envs;

     if (!$envs) {
          $envs = parse_ini_file('.env');
     }

     return empty($envs[$key]) ? null : $envs[$key];
}
