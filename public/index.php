<?php

use Hobocta\Transactions\Application;
use Hobocta\Transactions\ExceptionLogger;
use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../vendor/autoload.php';

try {
    (new Application(Request::createFromGlobals()))->run();
} catch (Exception $e) {
    ExceptionLogger::log($e);
    die(sprintf('Exception message: %s (%s:%s)', $e->getMessage(), $e->getFile(), $e->getLine()));
}
