<?php

namespace Hobocta\Transactions;

class Config
{
    public static function get()
    {
        /** @noinspection PhpIncludeInspection */
        return require sprintf('%s/config/config.php', Environment::getRootDir());
    }
}
