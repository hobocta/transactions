<?php

namespace Hobocta\Transactions;

class Config
{
    /**
     * @return array
     * @throws CommonException
     */
    public static function get(): array
    {
        /** @noinspection PhpIncludeInspection */
        $config = require sprintf('%s/config/config.php', Environment::getRootDir());

        if (!is_array($config)) {
            throw new CommonException('Unable to get config');
        }

        return $config;
    }
}
