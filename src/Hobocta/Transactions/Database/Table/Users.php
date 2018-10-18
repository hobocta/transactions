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
        if (empty($id)) {
            throw new CommonException('Empty id', ['id' => $id]);
        }

        /** @noinspection SqlResolve */
        return $this->database->query(
            "SELECT * FROM `{$this->tableName}` WHERE `id` = {$id}"
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
            throw new CommonException('Empty login', ['login' => $login]);
        }

        /** @noinspection SqlResolve */
        return $this->database->query(
            "SELECT * FROM `{$this->tableName}` WHERE `login` = {$this->database->pdo->quote($login)}"
        )->fetch();
    }

    /**
     * @param int $id
     * @param string $authHash
     * @throws CommonException
     */
    public function updateAuthHash(int $id, string $authHash)
    {
        if (empty($id)) {
            throw new CommonException('Empty id', ['id' => $id]);
        }

        if (empty($authHash)) {
            throw new CommonException('Empty authHash', ['id' => $id]);
        }

        /** @noinspection SqlResolve */
        $result = $this->database->query(
            "UPDATE `{$this->tableName}` SET `auth_hash` = {$this->database->pdo->quote($authHash)} WHERE `id` = {$id}"
        );
        if (!$result) {
            throw new CommonException('Unable to update user auth hash', ['id' => $id, 'result' => $result]);
        }
    }
}
