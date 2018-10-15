<?php
namespace Hobocta\Transactions;

class Environment
{
    public static function getRootDir(): string
    {
        return sprintf('%s/../../..', dirname(__FILE__));
    }
}
