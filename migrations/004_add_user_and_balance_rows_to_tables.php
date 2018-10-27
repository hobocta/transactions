<?php

use Hobocta\Transactions\Application;
use Hobocta\Transactions\CommonException;
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

    $database->startTransaction();

    try {
        /** @noinspection SqlResolve */
        $result = $database->pdo->prepare('
            INSERT INTO `users` (`login`, `password_hash`)
            VALUES (:login, :password_hash);
        ')->execute([
            ':login' => 'admin',
            ':password_hash' => password_hash('admin', PASSWORD_DEFAULT),
        ]);
        if (!$result) {
            throw new CommonException('Execute failed');
        }
        $userId = $database->pdo->lastInsertId();
        if (!$userId) {
            throw new CommonException('Unable to get lastInsertId');
        }

        /** @noinspection SqlResolve */
        $result = $database->pdo->prepare('
            INSERT INTO `balance` (`user_id`, `balance`)
            VALUES (:user_id, :balance);
        ')->execute([
            ':user_id' => $userId,
            ':balance' => 999999999,
        ]);
        if (!$result) {
            throw new CommonException('Execute failed');
        }

        $database->commit();

        echo 'Done' . PHP_EOL;
    } catch (\Exception $e) {
        $database->rollback();
        throw $e;
    }
} catch (\Exception $e) {
    ExceptionLogger::log($e);
    die(sprintf('Exception message: %s (%s:%s)', $e->getMessage(), $e->getFile(), $e->getLine()));
}
