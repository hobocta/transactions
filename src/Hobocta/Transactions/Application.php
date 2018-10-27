<?php

namespace Hobocta\Transactions;

use Hobocta\Transactions\Authorization;
use Hobocta\Transactions\Controller;
use Hobocta\Transactions\Database\Table;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;

class Application
{
    /**
     * @var ContainerBuilder
     */
    public $container;

    /**
     * @var Request
     */
    private $request;

    /**
     * Application constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->injectDependencies();
    }

    public function injectDependencies()
    {
        $this->container = new ContainerBuilder();

        foreach (
            [
                Config::class,
                Authorization\Authorization::class,
                Authorization\Session::class,
                Authorization\Cookie::class,
                Database\Database::class,
                Table\Balance::class,
                Table\Users::class,
                Table\BalanceLog::class,
                BalanceManager::class,
                Sum::class,
            ] as $className
        ) {
            $this->container->autowire($className)->setPublic(true);
        }

        foreach (
            [
                Controller\LoginGetController::class,
                Controller\LoginPostController::class,
                Controller\WithdrawCheckGetController::class,
                Controller\WithdrawCheckPostController::class,
                Controller\WithdrawConfirmPostController::class,
                Controller\LogoutPostController::class,
                Controller\LogoutPostController::class,
            ] as $className
        ) {
            $this->container->autowire($className)->setPublic(true)->setArgument(0, $this->request);
        }

        $this->container->compile();
    }

    /**
     * @throws CommonException
     * @throws \Exception
     */
    public function run()
    {
        /** @var Authorization\Authorization $authorization */
        $authorization = $this->container->get(Authorization\Authorization::class);
        $isAuthorized = $authorization->isAuthorized();

        $command = $this->request->request->get('command');

        if (!$isAuthorized && empty($command)) {
            /** @var Controller\LoginGetController $controller */
            $controller = $this->container->get(Controller\LoginGetController::class);
            $controller->action();
        } elseif (!$isAuthorized && $command === 'login') {
            /** @var Controller\LoginPostController $controller */
            $controller = $this->container->get(Controller\LoginPostController::class);
            $controller->action();
        } elseif ($isAuthorized && empty($command)) {
            /** @var Controller\WithdrawCheckGetController $controller */
            $controller = $this->container->get(Controller\WithdrawCheckGetController::class);
            $controller->action();
        } elseif ($isAuthorized && $command === 'withdrawCheck') {
            /** @var Controller\WithdrawCheckPostController $controller */
            $controller = $this->container->get(Controller\WithdrawCheckPostController::class);
            $controller->action();
        } elseif ($isAuthorized && $command === 'withdrawConfirm') {
            /** @var Controller\WithdrawConfirmPostController $controller */
            $controller = $this->container->get(Controller\WithdrawConfirmPostController::class);
            $controller->action();
        } elseif ($isAuthorized && $command === 'logout') {
            /** @var Controller\LogoutPostController $controller */
            $controller = $this->container->get(Controller\LogoutPostController::class);
            $controller->action();
        } elseif (!$isAuthorized) {
            /** @var Controller\RedirectToLoginController $controller */
            $controller = $this->container->get(Controller\RedirectToLoginController::class);
            $controller->action();
        } else {
            throw new CommonException(
                'Unknown command',
                ['isAuthorized' => $isAuthorized, 'command' => $command]
            );
        }
    }
}
