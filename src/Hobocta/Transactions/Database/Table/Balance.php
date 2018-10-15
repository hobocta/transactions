<?php

namespace Hobocta\Transactions\Database\Table;

use Hobocta\Transactions\CommonException;

class Balance extends AbstractTable
{
    private $tableName = 'balance';

    /**
     * @param $userId
     * @return mixed
     * @throws CommonException
     */
    public function getByUserId($userId)
    {
        /** @noinspection SqlResolve */
        $query = "SELECT * FROM `{$this->tableName}` WHERE `user_id` = {$userId} LIMIT 1";

        return $this->getByUserIdCommon($userId, $query);
    }

    /**
     * @param $userId
     * @return mixed
     * @throws CommonException
     */
    public function getByUserIdForUpdate($userId)
    {
        /** @noinspection SqlResolve */
        $query = "SELECT * FROM `{$this->tableName}` WHERE `user_id` = {$userId} LIMIT 1 FOR UPDATE";

        return $this->getByUserIdCommon($userId, $query);
    }

    /**
     * @param int $userId
     * @param string $query
     * @return mixed
     * @throws CommonException
     */
    private function getByUserIdCommon(int $userId, string $query)
    {
        $userId = (int)$userId;

        if (empty($userId)) {
            throw new CommonException('Empty user id');
        }

        $data = $this->database->query($query)->fetch();

        if (empty($data)) {
            throw new CommonException('Balance row was not found');
        }

        if (isset($data['balance'])) {
            $data['balance'] = (int)$data['balance'];
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
