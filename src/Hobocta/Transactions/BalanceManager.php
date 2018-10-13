<?php

namespace Hobocta\Transactions;

use Hobocta\Transactions\Database\Database;
use Hobocta\Transactions\Database\Table\Balance;
use Hobocta\Transactions\Database\Table\BalanceLog;

class BalanceManager
{
    private $database;
    private $balance;
    private $balanceLog;

    public function __construct(Database $database, Balance $balance, BalanceLog $balanceLog)
    {
        $this->database = $database;
        $this->balance = $balance;
        $this->balanceLog = $balanceLog;
    }

    /**
     * @param $balanceRowId
     * @param $withdraw
     * @throws CommonException
     */
    public function update($balanceRowId, $withdraw)
    {
        $this->database->startTransaction();

        $balance = $this->balance->getById($balanceRowId, true);

        $balanceNew = (float)$balance['balance'] - $withdraw;

        if ($balanceNew < 0) {
            throw new CommonException('Incorrect sum');
        }

        $this->balanceLog->add($balanceRowId, $balance['balance'], $balanceNew);

        $this->balance->updateBalance($balanceRowId, $balanceNew);

        $this->database->commit();
    }
}
