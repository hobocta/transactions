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

    $application->database->startTransaction();

    try {
        /** @noinspection SqlResolve */
        $result = $application->database->pdo->prepare('
            INSERT INTO `users` (`login`, `password_hash`)
            VALUES (:login, :password_hash);
        ')->execute([
            ':login' => 'admin',
            ':password_hash' => password_hash('admin', PASSWORD_DEFAULT),
        ]);
        if (!$result) {
            throw new CommonException('Execute failed');
        }
        $userId = $application->database->pdo->lastInsertId();
        if (!$userId) {
            throw new CommonException('Unable to get lastInsertId');
        }

        /** @noinspection SqlResolve */
        $result = $application->database->pdo->prepare('
            INSERT INTO `balance` (`user_id`, `balance`)
            VALUES (:user_id, :balance);
        ')->execute([
            ':user_id' => $userId,
            ':balance' => 999999999,
        ]);
        if (!$result) {
            throw new CommonException('Execute failed');
        }

        $application->database->commit();

        echo 'Done' . PHP_EOL;
    } catch (CommonException $e) {
        $application->database->rollback();
        throw $e;
    }
} catch (CommonException $e) {
    ExceptionLogger::log($e);
    die(sprintf('Exception message: %s (%s:%s)', $e->getMessage(), $e->getFile(), $e->getLine()));
}
