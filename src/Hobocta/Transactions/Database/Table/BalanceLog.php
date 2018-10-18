<?php

namespace Hobocta\Transactions\Database\Table;

use Hobocta\Transactions\CommonException;

class BalanceLog extends AbstractTable
{
    private $tableName = 'balance_log';

    /**
     * @param int $userId
     * @param int $balanceOld
     * @param int $balanceNew
     * @throws CommonException
     */
    public function add(int $userId, int $balanceOld, int $balanceNew)
    {
        if (empty($userId)) {
            throw new CommonException(
                'Empty userId',
                ['userId' => $userId, 'balanceOld' => $balanceOld, 'balanceNew' => $balanceNew]
            );
        }

        if ($balanceOld < 0) {
            throw new CommonException(
                'Incorrect balanceOld',
                ['userId' => $userId, 'balanceOld' => $balanceOld, 'balanceNew' => $balanceNew]
            );
        }

        if ($balanceNew < 0) {
            throw new CommonException(
                'Incorrect balanceNew',
                ['userId' => $userId, 'balanceOld' => $balanceOld, 'balanceNew' => $balanceNew]
            );
        }

        /** @noinspection SqlResolve */
        $result = $this->database->query(
            "INSERT INTO `{$this->tableName}` (user_id, created_at, balance_old, balance_new) VALUES ({$userId}, NOW(), {$balanceOld}, {$balanceNew})"
        );
        if (!$result) {
            throw new CommonException(
                'Unable to add balance log row',
                ['userId' => $userId, 'balanceOld' => $balanceOld, 'balanceNew' => $balanceNew, 'result' => $result]
            );
        }
    }
}
