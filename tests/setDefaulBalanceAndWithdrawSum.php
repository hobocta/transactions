<?php

use Hobocta\Transactions\Application;
use Hobocta\Transactions\Authorization\Hash;
use Hobocta\Transactions\CommonException;
use Hobocta\Transactions\Controller;
use Hobocta\Transactions\ExceptionLogger;

error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

require __DIR__ . '/../vendor/autoload.php';

try {
    $application = new Application();
    $application->injectDependency();

    $userId = 1;

    $newBalanceValue = 999999999;
    $balance = $application->balance->getByUserId($userId);
    $application->balance->updateBalance($balance['id'], $newBalanceValue);
    echo sprintf('Balance set %s', $newBalanceValue) . PHP_EOL;

    $hash = Hash::generate();
    $application->users->updateAuthHash($userId, $hash);
    $application->session->setUserId($userId);
    $application->session->setUserAuthHash($hash);
    $application->authorization->isAuthorized();
    $oldBalanceValue = 999999999;
    $withdrawValue = 333333333;
    $newBalanceValue = 666666666;
    $withdrawValueFormatted = $application->sum->format($withdrawValue);
    $_POST = array(
        'formToken' => Hash::generate(),
        'command' => 'withdraw',
        'balance' => (string)$oldBalanceValue,
        'sumToWithdraw' => $withdrawValueFormatted,
    );
    ob_start();
    (new Controller\PersonalPost($application, $_GET, $_POST))->action();
    ob_clean();
    $_POST = array(
        'formToken' => Hash::generate(),
        'command' => 'withdraw',
        'balance' => (string)$oldBalanceValue,
        'confirmed' => 'true',
        'sumToWithdraw' => $withdrawValueFormatted,
    );
    ob_start();
    (new Controller\PersonalPost($application, $_GET, $_POST))->action();
    ob_clean();
    echo sprintf('Requests to withdraw %s from balance sent', $newBalanceValue) . PHP_EOL;

    $application = new Application();
    $application->injectDependency();
    $balance = $application->balance->getById(1);
    echo sprintf('Balance is %s', $balance['balance']) . PHP_EOL;
    echo $balance['balance'] === (string)$newBalanceValue ? 'Test passed' : 'Test fail';
    echo PHP_EOL;
} catch (CommonException $e) {
    ExceptionLogger::log($e);
    die(sprintf('Exception message: %s (%s:%s)', $e->getMessage(), $e->getFile(), $e->getLine()));
}
