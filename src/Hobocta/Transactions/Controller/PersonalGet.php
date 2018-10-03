<?php

namespace Hobocta\Transactions\Controller;

use Hobocta\Transactions\CommonException;
use Hobocta\Transactions\Template;

class PersonalGet extends AbstractController
{
    /**
     * @throws \Hobocta\Transactions\CommonException
     */
    public function action()
    {
        $userData = $this->application->authorization->getUserData();
        $data['balance'] = $this->application->balance->getByUserId($userData['id']);
        if (empty($data['balance'])) {
            throw new CommonException('Unable to get balance');
        }

        (new Template('personal', $data))->render();
    }
}
