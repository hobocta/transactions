<?php

namespace Hobocta\Transactions\Controller;

use Hobocta\Transactions\CommonException;
use Hobocta\Transactions\Template;

class WithdrawCheckPostController extends AbstractWithdrawController
{
    /**
     * @throws CommonException
     */
    public function action()
    {
        $this->fillUserData();

        $this->data = [];

        $this->fillBalance();

        $this->validatePostFields();

        $this->fillSumToWithdraw();

        if (empty($this->data['errors'])) {
            $this->calculateBalanceNew();

            $this->data['needConfirm'] = true;
        }

        if (!empty($this->data['errors'])) {
            (new Template('withdrawCheck', $this->data))->render();
        } else {
            (new Template('withdrawConfirm', $this->data))->render();
        }
    }
}
