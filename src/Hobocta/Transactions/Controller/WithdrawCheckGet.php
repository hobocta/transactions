<?php

namespace Hobocta\Transactions\Controller;

use Hobocta\Transactions\CommonException;
use Hobocta\Transactions\Template;

class WithdrawCheckGet extends AbstractController
{
    /**
     * @throws \Hobocta\Transactions\CommonException
     */
    public function action()
    {
        $userData = $this->authorization->getUserData();
        $data['balance'] = $this->balance->getByUserId($userData['id']);
        if (empty($data['balance'])) {
            throw new CommonException('Unable to get balance');
        }

        (new Template('withdrawCheck', $data))->render();
    }
}
