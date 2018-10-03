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
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function getById($id)
    {
        $id = (int)$id;

        if (empty($id)) {
            throw new \Exception('Empty id');
        }

        /** @noinspection SqlResolve */
        return $this->database->query(
            "SELECT * FROM `users` WHERE `id` = {$id} LIMIT 1"
        )->fetch();
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
            "SELECT * FROM `users` WHERE `login` = {$this->database->pdo->quote($login)} LIMIT 1"
        )->fetch();
    }

    /**
     * @param $id
     * @param $authHash
     * @throws \Exception
     */
    public function updateAuthHash($id, $authHash)
    {
        $id = (int)$id;

        if (empty($id)) {
            throw new \Exception('Empty login');
        }

        if (empty($authHash)) {
            throw new \Exception('Empty authHash');
        }

        /** @noinspection SqlResolve */
        $result = $this->database->query(
            "UPDATE `users` SET `auth_hash` = {$this->database->pdo->quote($authHash)} WHERE `id` = {$id} LIMIT 1"
        )->execute();

        if (!$result) {
            throw new \Exception('Unable to update user auth hash');
        }
    }
}
