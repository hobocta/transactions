<?php

namespace Hobocta\Transactions\Controller;

use Hobocta\Transactions\CommonException;
use Hobocta\Transactions\Template;

class WithdrawCheckGetController extends AbstractWithdrawController
{
    /**
     * @throws CommonException
     */
    public function action()
    {
        $this->fillUserData();

        $this->data = [];

        $this->fillBalance();

        (new Template('withdrawCheck', $this->data))->render();
    }
}
