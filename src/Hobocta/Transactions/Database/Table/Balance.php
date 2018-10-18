<?php

namespace Hobocta\Transactions\Database\Table;

use Hobocta\Transactions\CommonException;

class Balance extends AbstractTable
{
    private $tableName = 'balance';

    /**
     * @param int $userId
     * @return array
     * @throws CommonException
     */
    public function getByUserId(int $userId): array
    {
        /** @noinspection SqlResolve */
        $query = "SELECT * FROM `{$this->tableName}` WHERE `user_id` = {$userId}";

        return $this->getByUserIdCommon($userId, $query);
    }

    /**
     * @param int $userId
     * @return array
     * @throws CommonException
     */
    public function getByUserIdForUpdate(int $userId): array
    {
        /** @noinspection SqlResolve */
        $query = "SELECT * FROM `{$this->tableName}` WHERE `user_id` = {$userId} FOR UPDATE";

        return $this->getByUserIdCommon($userId, $query);
    }

    /**
     * @param int $userId
     * @param string $query
     * @return array
     * @throws CommonException
     */
    private function getByUserIdCommon(int $userId, string $query): array
    {
        if (empty($userId)) {
            throw new CommonException('Empty user id', ['userId' => $userId]);
        }

        $data = $this->database->query($query)->fetch();

        if (empty($data)) {
            throw new CommonException('Balance row was not found', ['userId' => $userId]);
        }

        if (!is_array($data)) {
            throw new CommonException('Data is non array', ['userId' => $userId]);
        }

        if (isset($data['balance'])) {
            $data['balance'] = (int)$data['balance'];
            $data['balanceFormatted'] = $this->sum->format($data['balance']);
        }

        return $data;
    }

    /**
     * @param int $id
     * @param int $balance
     * @throws CommonException
     */
    public function updateBalance(int $id, int $balance)
    {
        if (empty($id)) {
            throw new CommonException('Empty id', ['id' => $id, 'balance' => $balance]);
        }

        if ($balance < 0) {
            throw new CommonException('New balance is negative', ['id' => $id, 'balance' => $balance]);
        }

        /** @noinspection SqlResolve */
        $result = $this->database->query(
            "UPDATE `{$this->tableName}` SET `balance` = {$balance} WHERE `id` = {$id}"
        );
        if (!$result) {
            throw new CommonException(
                'Unable to update user balance',
                ['id' => $id, 'balance' => $balance, 'result' => $result]
            );
        }
    }
}
