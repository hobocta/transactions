<?php

namespace Hobocta\Transactions\Controller;

use Hobocta\Transactions\CommonException;
use Hobocta\Transactions\Template;

class WithdrawCheckPost extends AbstractPersonalPost
{
    /**
     * @throws CommonException
     */
    public function action()
    {
        $this->fillUserData();

        $this->data = [];

        $this->fillBalance($this->userData['id']);

        $this->validatePostFields();

        $this->fillSumToWithdraw();

        if (empty($this->data['errors'])) {
            $this->calculateBalanceNew();

            $this->data['needConfirm'] = true;
        }

        if (!empty($this->data['errors'])) {
            (new Template('withdrawCheck', $this->data))->render();
        } elseif (!$this->data['updated']) {
            (new Template('withdrawConfirm', $this->data))->render();
        }
    }
}
