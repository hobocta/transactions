<?php

namespace Hobocta\Transactions;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class ExceptionLogger
{
    public static function log(\Exception $e)
    {
        $logger = new Logger('main');

        $logger->setHandlers([
            new RotatingFileHandler(sprintf('%s/logs/exceptions.log', Environment::getRootDir()))
        ]);

        $logger->error(
            sprintf(
                'Exception message: %s (%s:%s), trace: %s',
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                $e->getTraceAsString()
            ),
            isset($e->data) ? $e->data : []
        );
    }
}
