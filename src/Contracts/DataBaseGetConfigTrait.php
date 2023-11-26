<?php

namespace App\Contracts;
use App\Helpers\Config;

trait DatabaseGetConfigTrait
{
    private function getConfig()
    {
        return Config::get('Database', 'pdo_testing');
    }
}