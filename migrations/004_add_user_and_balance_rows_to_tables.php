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
    /** @noinspection SqlResolve */
    $pdoStatement = $application->database->pdo->prepare('
        INSERT INTO `users` (`login`, `password_hash`)
        VALUES (:login, :password_hash);
    ');
    $pdoStatement->execute([
        ':login' => 'admin',
        ':password_hash' => password_hash('admin', PASSWORD_DEFAULT),
    ]);
    $userId = $application->database->pdo->lastInsertId();

    /** @noinspection SqlResolve */
    $pdoStatement = $application->database->pdo->prepare('
        INSERT INTO `balance` (`user_id`, `balance`)
        VALUES (:user_id, :balance);
    ');
    $pdoStatement->execute([
        ':user_id' => $userId,
        ':balance' => 999999999,
    ]);
    echo 'Done' . PHP_EOL;
} catch (CommonException $e) {
    ExceptionLogger::log($e);
    die(sprintf('Exception message: %s (%s:%s)', $e->getMessage(), $e->getFile(), $e->getLine()));
}
