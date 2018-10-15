<?php

namespace Hobocta\Transactions\Controller;

class RedirectToLoginController extends AbstractController
{
    public function action()
    {
        header('Refresh:0');
        die();
    }
}
