<?php

namespace Hobocta\Transactions\Controller;

use Hobocta\Transactions\CommonException;

class LogoutPost extends AbstractController
{
    /**
     * @throws CommonException
     */
    public function action()
    {
        $this->authorization->logout();
        header('Refresh:0');
        die();
    }
}
