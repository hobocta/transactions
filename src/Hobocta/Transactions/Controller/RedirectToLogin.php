<?php

namespace Hobocta\Transactions\Controller;

class RedirectToLogin extends AbstractController
{
    public function action()
    {
        header('Refresh:0');
        die();
    }
}
