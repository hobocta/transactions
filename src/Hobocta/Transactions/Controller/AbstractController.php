<?php

namespace Hobocta\Transactions\Controller;

use Hobocta\Transactions\Application;

class AbstractController
{
    protected $application;
    protected $getData;
    protected $postData;
    protected $sum;

    public function __construct(Application $application, array $getData, array $postData)
    {
        $this->application = $application;
        $this->getData = $getData;
        $this->postData = $postData;
    }
}
