<?php

use Hobocta\Transactions\Application;
use Hobocta\Transactions\ExceptionLogger;

if (!defined('STDIN')) {
    die('Access is denied');
}

require __DIR__ . '/../vendor/autoload.php';

try {
    $application = new Application();

    /** @var \Hobocta\Transactions\Database\Database $database */
    $database = $application->container->get('database');

    $database->query('
        CREATE TABLE IF NOT EXISTS balance (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) NOT NULL,
            `balance` bigint NOT NULL,
            PRIMARY KEY (`id`),
            INDEX (`user_id`)
        ) COLLATE="utf8_general_ci" ENGINE=InnoDB;
    ');
    /** @noinspection SqlResolve */
    $database->query('
        ALTER TABLE `balance`
        ADD CONSTRAINT `FK_balance_user_id`
        FOREIGN KEY (`user_id`)
        REFERENCES `users` (`id`) ON UPDATE RESTRICT ON DELETE CASCADE;
    ');
    echo 'Done' . PHP_EOL;
} catch (\Exception $e) {
    ExceptionLogger::log($e);
    die(sprintf('Exception message: %s (%s:%s)', $e->getMessage(), $e->getFile(), $e->getLine()));
}
