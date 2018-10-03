<?php

namespace Hobocta\Transactions;

class BalanceManager
{
    private $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @param $balanceRowId
     * @param $withdraw
     * @throws CommonException
     */
    public function update($balanceRowId, $withdraw)
    {
        $this->application->database->startTransaction();

        $balance = $this->application->balance->getById($balanceRowId);

        $balanceNew = (float)$balance['balance'] - $withdraw;

        if ($balanceNew < 0) {
            throw new CommonException('Incorrect sum');
        }

        $this->application->balanceLog->add($balanceRowId, $balance['balance'], $balanceNew);

        $this->application->balance->updateBalance($balanceRowId, $balanceNew);

        $this->application->database->commit();
    }
}
