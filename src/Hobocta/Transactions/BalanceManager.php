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
     * @param int $balanceRowId
     * @param int $balanceOld
     * @param int $balanceNew
     * @throws CommonException
     */
    public function update(int $balanceRowId, int $balanceOld, int $balanceNew)
    {
        $this->balanceLog->add($balanceRowId, $balanceOld, $balanceNew);

        $this->balance->updateBalance($balanceRowId, $balanceNew);
    }
}
