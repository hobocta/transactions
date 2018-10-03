<?php

namespace Hobocta\Transactions;

use Hobocta\Transactions\Controller;

class Application
{
    public $session;
    public $cookie;
    public $database;
    public $users;

    /**
     * @var Authorization
     */
    public $authorization;

    /**
     * Application constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->session = new Session();
        $this->cookie = new Cookie();
        $this->database = new Database();
        $this->users = new Users($this->database);
    }

    /**
     * @throws \Exception
     */
    public function run()
    {
        $this->authorization = new Authorization($this);

        if (!$this->authorization->isAuthorized()) {
            if (empty($_POST['command'])) {
                (new Controller\LoginGet($this, $_GET, $_POST))->action();
            } elseif ($_POST['command'] === 'login') {
                (new Controller\LoginPost($this, $_GET, $_POST))->action();
            } else {
                throw new \Exception('Unknown command');
            }
        } else {
            if (empty($_POST['command'])) {
                // @todo
            } elseif ($_POST['command'] === 'withdraw') {
                // @todo
            } else {
                throw new \Exception('Unknown command');
            }
        }
    }
}
