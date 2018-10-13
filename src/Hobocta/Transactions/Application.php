<?php

namespace Hobocta\Transactions;

use Hobocta\Transactions\Authorization;
use Hobocta\Transactions\Controller;
use Hobocta\Transactions\Database\Table;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class Application
{
    public $config;

    /**
     * @var ContainerBuilder
     */
    public $container;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        $this->config = Config::get();
        $this->injectDependencies();
    }

    public function injectDependencies()
    {
        $this->container = new ContainerBuilder();
        $this->container->register('session', Authorization\Session::class);
        $this->container->register('cookie', Authorization\Cookie::class);
        $this->container->register('database', Database\Database::class)
            ->addArgument($this->config['database']);
        $this->container->register('sum', Sum::class)
            ->addArgument($this->config['decimals']);

        $tableArguments = [
            new Reference('database'),
            new Reference('sum'),
        ];
        $this->container->register('balance', Table\Balance::class)
            ->setArguments($tableArguments);
        $this->container->register('users', Table\Users::class)
            ->setArguments($tableArguments);
        $this->container->register('balanceLog', Table\BalanceLog::class)
            ->setArguments($tableArguments);
        $this->container->register('authorization', Authorization\Authorization::class)
            ->setArguments([
                new Reference('session'),
                new Reference('cookie'),
                new Reference('users'),
            ]);
        $this->container->register('balanceManager', BalanceManager::class)
            ->setArguments([
                new Reference('database'),
                new Reference('balance'),
                new Reference('balanceLog'),
            ]);

        $controllerArguments = [
            new Reference('authorization'),
            new Reference('session'),
            new Reference('users'),
            new Reference('balance'),
            new Reference('balanceManager'),
            new Reference('sum'),
            $_GET,
            $_POST,
        ];
        $this->container->register('loginGetController', Controller\LoginGet::class)
            ->setArguments($controllerArguments);
        $this->container->register('loginPostController', Controller\LoginPost::class)
            ->setArguments($controllerArguments);
        $this->container->register('personalGetController', Controller\PersonalGet::class)
            ->setArguments($controllerArguments);
        $this->container->register('personalPostController', Controller\PersonalPost::class)
            ->setArguments($controllerArguments);
        $this->container->register('logoutPostController', Controller\LogoutPost::class)
            ->setArguments($controllerArguments);
    }

    /**
     * @throws CommonException
     * @throws \Exception
     */
    public function run()
    {
        /** @var Authorization\Authorization $authorization */
        $authorization = $this->container->get('authorization');

        if (!$authorization->isAuthorized()) {
            if (empty($_POST['command'])) {
                /** @var Controller\LoginGet $loginGetController */
                $loginGetController = $this->container->get('loginGetController');
                $loginGetController->action();
            } elseif ($_POST['command'] === 'login') {
                /** @var Controller\LoginPost $loginPostController */
                $loginPostController = $this->container->get('loginPostController');
                $loginPostController->action();
            } else {
                throw new CommonException('Unknown command');
            }
        } else {
            if (empty($_POST['command'])) {
                /** @var Controller\PersonalGet $personalGetController */
                $personalGetController = $this->container->get('personalGetController');
                $personalGetController->action();
            } elseif ($_POST['command'] === 'withdraw') {
                /** @var Controller\PersonalPost $personalPostController */
                $personalPostController = $this->container->get('personalPostController');
                $personalPostController->action();
            } elseif ($_POST['command'] === 'logout') {
                /** @var Controller\LogoutPost $logoutPostController */
                $logoutPostController = $this->container->get('logoutPostController');
                $logoutPostController->action();
            } else {
                throw new CommonException('Unknown command');
            }
        }
    }
}
