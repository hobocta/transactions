<?php
namespace Hobocta\Transactions;

class Environment
{
    public static function getRootDir()
    {
        return sprintf('%s/../..', dirname(__FILE__));
    }
}
