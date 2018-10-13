<?php

use Hobocta\Transactions\Application;
use Hobocta\Transactions\Authorization\Hash;
use Hobocta\Transactions\Controller;
use Hobocta\Transactions\ExceptionLogger;

error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

require __DIR__ . '/../vendor/autoload.php';

try {
    $application = new Application();

    $userId = 1;

    $newBalanceValue = 999999999;
    $balance = $application->container->get('balance')->getByUserId($userId);
    $application->container->get('balance')->updateBalance($balance['id'], $newBalanceValue);
    echo sprintf('Balance set %s', $newBalanceValue) . PHP_EOL;

    $hash = Hash::generate();
    $application->container->get('users')->updateAuthHash($userId, $hash);
    $application->container->get('session')->setUserId($userId);
    $application->container->get('session')->setUserAuthHash($hash);
    $application->container->get('authorization')->isAuthorized();
    $oldBalanceValue = 999999999;
    $withdrawValue = 333333333;
    $newBalanceValue = 666666666;
    $withdrawValueFormatted = $application->container->get('sum')->format($withdrawValue);
    $_POST = array(
        'formToken' => Hash::generate(),
        'command' => 'withdraw',
        'balance' => (string)$oldBalanceValue,
        'sumToWithdraw' => $withdrawValueFormatted,
    );
    ob_start();
    /** @noinspection PhpParamsInspection */
    (new Controller\PersonalPost(
        $application->container->get('authorization'),
        $application->container->get('session'),
        $application->container->get('users'),
        $application->container->get('balance'),
        $application->container->get('balanceManager'),
        $application->container->get('sum'),
        $_GET,
        $_POST
    ))->action();
    ob_clean();
    $_POST = array(
        'formToken' => Hash::generate(),
        'command' => 'withdraw',
        'balance' => (string)$oldBalanceValue,
        'confirmed' => 'true',
        'sumToWithdraw' => $withdrawValueFormatted,
    );
    ob_start();
    /** @noinspection PhpParamsInspection */
    (new Controller\PersonalPost(
        $application->container->get('authorization'),
        $application->container->get('session'),
        $application->container->get('users'),
        $application->container->get('balance'),
        $application->container->get('balanceManager'),
        $application->container->get('sum'),
        $_GET,
        $_POST
    ))->action();
    ob_clean();
    echo sprintf('Requests to withdraw %s from balance sent', $newBalanceValue) . PHP_EOL;

    $balance = $application->container->get('balance')->getById(1);
    echo sprintf('Balance is %s', $balance['balance']) . PHP_EOL;
    echo $balance['balance'] === (string)$newBalanceValue ? 'Test passed' : 'Test fail';
    echo PHP_EOL;
} catch (Exception $e) {
    ExceptionLogger::log($e);
    die(sprintf('Exception message: %s (%s:%s)', $e->getMessage(), $e->getFile(), $e->getLine()));
}
