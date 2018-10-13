<?php

namespace Hobocta\Transactions\Database\Table;

use Hobocta\Transactions\CommonException;

class Balance extends AbstractTable
{
    private $tableName = 'balance';

    /**
     * @param $id
     * @param bool $forUpdate
     * @return mixed
     * @throws CommonException
     */
    public function getById($id, $forUpdate = false)
    {
        $id = (int)$id;

        if (empty($id)) {
            throw new CommonException('Empty id');
        }

        /** @noinspection SqlResolve */
        $data = $this->database->query(
            "SELECT * FROM `{$this->tableName}` WHERE `id` = {$id} LIMIT 1" . ($forUpdate ? ' FOR UPDATE' : '')
        )->fetch();

        if (empty($data)) {
            throw new CommonException('Balance row was not found');
        }

        if (isset($data['balance'])) {
            $data['balanceFormatted'] = $this->sum->format($data['balance']);
        }

        return $data;
    }

    /**
     * @param $id
     * @return array
     * @throws CommonException
     */
    public function getByUserId($id)
    {
        $id = (int)$id;

        if (empty($id)) {
            throw new CommonException('Empty id');
        }

        /** @noinspection SqlResolve */
        $data = $this->database->query(
            "SELECT * FROM `{$this->tableName}` WHERE `user_id` = {$id} LIMIT 1"
        )->fetch();

        if (empty($data)) {
            throw new CommonException('Balance row was not found');
        }

        if (isset($data['balance'])) {
            $data['balanceFormatted'] = $this->sum->format($data['balance']);
        }

        return $data;
    }

    /**
     * @param $id
     * @param $balance
     * @throws CommonException
     */
    public function updateBalance($id, $balance)
    {
        $id = (int)$id;

        if (empty($id)) {
            throw new CommonException('Empty login');
        }

        $balance = (float)$balance;

        if ($balance < 0) {
            throw new CommonException('Incorrect $balance');
        }

        /** @noinspection SqlResolve */
        $result = $this->database->query(
            "UPDATE `{$this->tableName}` SET `balance` = {$balance} WHERE `id` = {$id} LIMIT 1"
        );
        if (!$result) {
            throw new CommonException('Unable to update user balance');
        }
    }
}
