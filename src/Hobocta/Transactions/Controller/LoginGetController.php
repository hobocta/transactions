<?php

namespace Hobocta\Transactions\Controller;

use Hobocta\Transactions\Template;

class LoginGetController extends AbstractController
{
    public function action()
    {
        (new Template('login'))->render();
    }
}
