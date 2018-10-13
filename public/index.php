<?php

use Hobocta\Transactions\Application;
use Hobocta\Transactions\ExceptionLogger;

require __DIR__ . '/../vendor/autoload.php';

try {
    (new Application())->run();
} catch (Exception $e) {
    ExceptionLogger::log($e);
    die(sprintf('Exception message: %s (%s:%s)', $e->getMessage(), $e->getFile(), $e->getLine()));
}
