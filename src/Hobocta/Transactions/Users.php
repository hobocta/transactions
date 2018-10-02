<?php

namespace Hobocta\Transactions;

class Users
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * @param $login
     * @return mixed
     * @throws \Exception
     */
    public function getByLogin($login)
    {
        if (empty($login)) {
            throw new \Exception('Empty login');
        }

        /** @noinspection SqlResolve */
        return $this->database->query(
            'SELECT * FROM `users` WHERE `user_login` = ' . $this->database->pdo->quote($login) . ' LIMIT 1'
        )->fetch();
    }
}
