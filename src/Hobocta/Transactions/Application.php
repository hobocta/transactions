<?php

namespace Hobocta\Transactions;

use Hobocta\Transactions\Authorization;
use Hobocta\Transactions\Controller;
use Hobocta\Transactions\Database\Database;
use Hobocta\Transactions\Database\Table;

class Application
{
    public $session;
    public $cookie;
    public $database;
    public $users;

    /**
     * @var Authorization\Authorization
     */
    public $authorization;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        $this->session = new Authorization\Session();
        $this->cookie = new Authorization\Cookie();
        $this->database = new Database();
        $this->users = new Table\Users($this->database);
    }

    /**
     * @throws CommonException
     */
    public function run()
    {
        $this->authorization = new Authorization\Authorization($this);

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
                // @todo
            } elseif ($_POST['command'] === 'withdraw') {
                // @todo
            } else {
                throw new CommonException('Unknown command');
            }
        }
    }
}
