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
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `login` varchar(30) NOT NULL UNIQUE,
            `password_hash` varchar(255) NOT NULL,
            `auth_hash` varchar(255) UNIQUE,
            PRIMARY KEY (`id`),
            INDEX (`login`)
        );
    ');
    echo 'Done' . PHP_EOL;
} catch (\Exception $e) {
    ExceptionLogger::log($e);
    die(sprintf('Exception message: %s (%s:%s)', $e->getMessage(), $e->getFile(), $e->getLine()));
}
