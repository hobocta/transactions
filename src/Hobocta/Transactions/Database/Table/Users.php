<?php

namespace Hobocta\Transactions\Database\Table;

use Hobocta\Transactions\CommonException;

class Users extends AbstractTable
{
    private $tableName = 'users';

    /**
     * @param int $id
     * @return mixed
     * @throws CommonException
     */
    public function getById(int $id)
    {
        $id = (int)$id;

        if (empty($id)) {
            throw new CommonException('Empty id');
        }

        /** @noinspection SqlResolve */
        return $this->database->query(
            "SELECT * FROM `{$this->tableName}` WHERE `id` = {$id} LIMIT 1"
        )->fetch();
    }

    /**
     * @param string $login
     * @return mixed
     * @throws CommonException
     */
    public function getByLogin(string $login)
    {
        if (empty($login)) {
            throw new CommonException('Empty login');
        }

        /** @noinspection SqlResolve */
        return $this->database->query(
            "SELECT * FROM `{$this->tableName}` WHERE `login` = {$this->database->pdo->quote($login)} LIMIT 1"
        )->fetch();
    }

    /**
     * @param int $id
     * @param string $authHash
     * @throws CommonException
     */
    public function updateAuthHash(int $id, string $authHash)
    {
        $id = (int)$id;

        if (empty($id)) {
            throw new CommonException('Empty login');
        }

        if (empty($authHash)) {
            throw new CommonException('Empty authHash');
        }

        /** @noinspection SqlResolve */
        $result = $this->database->query(
            "UPDATE `{$this->tableName}` SET `auth_hash` = {$this->database->pdo->quote($authHash)} WHERE `id` = {$id} LIMIT 1"
        );

        if (!$result) {
            throw new CommonException('Unable to update user auth hash');
        }
    }
}
