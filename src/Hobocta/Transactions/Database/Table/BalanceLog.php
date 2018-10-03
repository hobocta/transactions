<?php

namespace Hobocta\Transactions\Database\Table;

use Hobocta\Transactions\CommonException;

class BalanceLog extends AbstractTable
{
    private $tableName = 'balance_log';

    /**
     * @param $userId
     * @param $balanceOld
     * @param $balanceNew
     * @throws CommonException
     */
    public function add($userId, $balanceOld, $balanceNew)
    {
        $userId = (int)$userId;
        if (empty($userId)) {
            throw new CommonException('Empty userId');
        }

        $balanceOld = (float)$balanceOld;
        if ($balanceOld < 0) {
            throw new CommonException('Incorrect balanceOld');
        }

        $balanceNew = (float)$balanceNew;
        if ($balanceNew < 0) {
            throw new CommonException('Incorrect balanceNew');
        }

        /** @noinspection SqlResolve */
        $result = $this->database->query(
            "INSERT INTO `{$this->tableName}` (user_id, created_at, balance_old, balance_new) VALUES ({$userId}, NOW(), {$balanceOld}, {$balanceNew})"
        );
        if (!$result) {
            throw new CommonException('Unable to add balance log row');
        }
    }
}
