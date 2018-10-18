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
            $e->getMessage(),
            [
                'code' => $e->getCode(),
                'trace' => $e->getTrace(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'data' => isset($e->data) ? $e->data : [],
            ]
        );
    }
}
