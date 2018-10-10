<?php

namespace Hobocta\Transactions;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class ExceptionLogger
{
    public static function log(CommonException $e)
    {
        $logger = new Logger('main');

        $logger->setHandlers(
            array(
                new RotatingFileHandler('../logs/exceptions.log'),
            )
        );

        $logger->error(
            sprintf(
                'Exception message: %s (%s:%s), trace: %s',
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                $e->getTraceAsString()
            )
        );
    }
}
