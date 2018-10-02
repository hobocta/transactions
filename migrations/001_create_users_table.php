<?php

use Hobocta\Transactions\Application;
use Hobocta\Transactions\ExceptionLogger;

if (!defined('STDIN')) {
    die('Access is denied');
}

require __DIR__ . '/../vendor/autoload.php';

try {
    $application = new Application();
    $application->database->query('
        CREATE TABLE IF NOT EXISTS users (
            `user_id` int(11) NOT NULL AUTO_INCREMENT,
            `user_login` varchar(30) NOT NULL UNIQUE,
            `user_password` varchar(255) NOT NULL,
            `user_hash` varchar(255),
            PRIMARY KEY (`user_id`),
            INDEX (`user_login`)
        );
    ');
    echo 'Done' . PHP_EOL;
} catch (\Exception $e) {
    ExceptionLogger::log($e);
    die(sprintf('Exception message: %s (%s:%s)', $e->getMessage(), $e->getFile(), $e->getLine()));
}
