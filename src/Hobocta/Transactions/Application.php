<?php

namespace Hobocta\Transactions;

class Application
{
    public $session;
    public $database;
    public $users;

    /**
     * Application constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->session = new Session();
        $this->database = new Database();
        $this->users = new Users($this->database);
    }

    /**
     * @throws \Exception
     */
    public function run()
    {
        if (empty($_POST['command'])) {
            include __DIR__ . '/../../../templates/login.php';
        } elseif ($_POST['command'] === 'login') {
            $data = [];

            if (empty($_POST['login'])) {
                $data['errors'][] = 'Field login is not filled';
            }

            if (empty($_POST['password'])) {
                $data['errors'][] = 'Field password is not filled';
            }

            $user = $this->users->getByLogin($_POST['login']);
            if ($user === false || !password_verify($_POST['password'], $user['user_password'])) {
                $data['errors'][] = 'Login or password is not correct';
            }

            if (!empty($data['errors'])) {
                include __DIR__ . '/../../../templates/login.php';
            } else {
                // @todo создать хэш и сохранить его в базу
            }
        } else {
            throw new \Exception('Unknown command');
        }
    }
}
