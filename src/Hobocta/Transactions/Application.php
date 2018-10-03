<?php

namespace Hobocta\Transactions;

use Hobocta\Transactions\Authorization;
use Hobocta\Transactions\Controller;
use Hobocta\Transactions\Database\Database;
use Hobocta\Transactions\Database\Table;

class Application
{
    public $config;
    public $session;
    public $cookie;
    public $database;
    public $users;
    public $balance;
    public $balanceLog;

    /**
     * @var Authorization\Authorization
     */
    public $authorization;

    /**
     * @var BalanceManager
     */
    public $balanceManager;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        $this->config = Config::get();
        $this->session = new Authorization\Session();
        $this->cookie = new Authorization\Cookie();
        $this->database = new Database($this->config['database']);
        $this->users = new Table\Users($this->database);
        $this->balance = new Table\Balance($this->database);
        $this->balanceLog = new Table\BalanceLog($this->database);
    }

    /**
     * @throws CommonException
     */
    public function run()
    {
        $this->authorization = new Authorization\Authorization($this);
        $this->balanceManager = new BalanceManager($this);

        if (!$this->authorization->isAuthorized()) {
            if (empty($_POST['command'])) {
                (new Controller\LoginGet($this, $_GET, $_POST))->action();
            } elseif ($_POST['command'] === 'login') {
                (new Controller\LoginPost($this, $_GET, $_POST))->action();
            } else {
                throw new CommonException('Unknown command');
            }
        } else {
            if (empty($_POST['command'])) {
                (new Controller\PersonalGet($this, $_GET, $_POST))->action();
            } elseif ($_POST['command'] === 'withdraw') {
                (new Controller\PersonalWithdrawPost($this, $_GET, $_POST))->action();
            } elseif ($_POST['command'] === 'logout') {
                (new Controller\LogoutPost($this, $_GET, $_POST))->action();
            } else {
                throw new CommonException('Unknown command');
            }
        }
    }
}
