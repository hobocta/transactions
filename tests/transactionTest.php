<?php

use Hobocta\Transactions\Application;
use Hobocta\Transactions\Authorization\Hash;
use Hobocta\Transactions\CommonException;
use Hobocta\Transactions\Controller;
use Hobocta\Transactions\ExceptionLogger;

require __DIR__ . '/../vendor/autoload.php';

try {
    $application = new Application();
    $application->injectDependency();
    $userId = 1;
    $hash = Hash::generate();
    $application->users->updateAuthHash($userId, $hash);
    $application->session->setUserId($userId);
    $application->session->setUserAuthHash($hash);
    $application->authorization->isAuthorized();
    $_POST = array (
        'formToken' => 'xAMyo6NOHdvXMMJ9JPT/SOuVNPRvSgd73hzqIh+d',
        'command' => 'withdraw',
        'balance' => '999999999',
        'sumToWithdraw' => '333,333333',
    );
    (new Controller\PersonalPost($application, $_GET, $_POST))->action();
    $_POST = array (
        'formToken' => 'apysHMMhJafkuzuwUAVWuG6il/IlNrdTbiMv0gIj',
        'command' => 'withdraw',
        'balance' => '999999999',
        'confirmed' => 'true',
        'sumToWithdraw' => '333,333333',
    );
    (new Controller\PersonalPost($application, $_GET, $_POST))->action();
} catch (CommonException $e) {
    ExceptionLogger::log($e);
    die(sprintf('Exception message: %s (%s:%s)', $e->getMessage(), $e->getFile(), $e->getLine()));
}
