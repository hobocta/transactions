<?php

namespace Hobocta\Transactions\Config;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class Config
{
    private $config = [];

    /**
     * Config constructor.
     * @throws CommonException
     */
    public function __construct()
    {
        $this->config = (new Processor())->processConfiguration(
            new RootConfiguration(),
            Yaml::parse(file_get_contents(__DIR__ . '/../../../../config/config.yaml'))
        );
    }

    public function get(string $name)
    {
        return $this->config[$name] ?? null;
    }
}
