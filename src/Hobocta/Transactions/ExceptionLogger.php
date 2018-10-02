<?php

namespace Hobocta\Transactions;

use Monolog\Handler\RotatingFileHandler;

class ExceptionLogger
{
    public static function log(\Exception $e)
    {
        $logger = new \Monolog\Logger('main');

        $logger->setHandlers(
            array(
                new RotatingFileHandler('logs/exceptions.log'),
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
