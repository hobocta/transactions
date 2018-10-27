<?php

use Hobocta\Transactions\Application;
use Hobocta\Transactions\Database\Database;
use Hobocta\Transactions\ExceptionLogger;
use Symfony\Component\HttpFoundation\Request;

if (!defined('STDIN')) {
    die('Access is denied');
}

require __DIR__ . '/../vendor/autoload.php';

try {
    $application = new Application(new Request());

    /** @var Database $database */
    $database = $application->container->get(Database::class);

    $database->query('
        CREATE TABLE IF NOT EXISTS balance_log (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) NOT NULL,
            `created_at` datetime NOT NULL,
            `balance_old` bigint NOT NULL,
            `balance_new` bigint NOT NULL,
            PRIMARY KEY (`id`),
            INDEX (`user_id`)
        ) COLLATE="utf8_general_ci" ENGINE=InnoDB;
    ');
    echo 'Done' . PHP_EOL;
} catch (\Exception $e) {
    ExceptionLogger::log($e);
    die(sprintf('Exception message: %s (%s:%s)', $e->getMessage(), $e->getFile(), $e->getLine()));
}
