<?php

namespace Hobocta\Transactions;

class Config
{
    private $config = [];

    /**
     * Config constructor.
     * @throws CommonException
     */
    public function __construct()
    {
        /** @noinspection PhpIncludeInspection */
        $this->config = require sprintf('%s/config/config.php', Environment::getRootDir());

        if (!is_array($this->config)) {
            throw new CommonException('Unable to get config');
        }
    }

    public function get($name)
    {
        return $this->config[$name] ?? null;
    }
}
