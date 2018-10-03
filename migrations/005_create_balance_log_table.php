<?php

use Hobocta\Transactions\Application;
use Hobocta\Transactions\CommonException;
use Hobocta\Transactions\ExceptionLogger;

if (!defined('STDIN')) {
    die('Access is denied');
}

require __DIR__ . '/../vendor/autoload.php';

try {
    $application = new Application();
    $application->database->query('
        CREATE TABLE IF NOT EXISTS balance_log (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) NOT NULL,
            `created_at` datetime NOT NULL,
            `balance_old` decimal(19,2) NOT NULL,
            `balance_new` decimal(19,2) NOT NULL,
            PRIMARY KEY (`id`),
            INDEX (`user_id`)
        );
    ');
    echo 'Done' . PHP_EOL;
} catch (CommonException $e) {
    ExceptionLogger::log($e);
    die(sprintf('Exception message: %s (%s:%s)', $e->getMessage(), $e->getFile(), $e->getLine()));
}
