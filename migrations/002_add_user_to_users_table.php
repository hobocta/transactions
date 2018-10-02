<?php

use Hobocta\Transactions\Application;
use Hobocta\Transactions\ExceptionLogger;

if (!defined('STDIN')) {
    die('Access is denied');
}

require __DIR__ . '/../vendor/autoload.php';

try {
    $application = new Application();
    /** @noinspection SqlResolve */
    $application->database->query('
        INSERT INTO `users` (`user_login`, `user_password`)
        VALUES ("admin", "' . password_hash('admin', PASSWORD_DEFAULT) . '");
    ');
    echo 'Done' . PHP_EOL;
} catch (\Exception $e) {
    ExceptionLogger::log($e);
    die(sprintf('Exception message: %s (%s:%s)', $e->getMessage(), $e->getFile(), $e->getLine()));
}
