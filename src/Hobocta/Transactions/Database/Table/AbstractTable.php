<?php

namespace Hobocta\Transactions\Database\Table;

use Hobocta\Transactions\Application;
use Hobocta\Transactions\Database\Database;

class AbstractTable
{
    protected $database;
    protected $application;

    public function __construct(Application $application, Database $database)
    {
        $this->application = $application;
        $this->database = $database;
    }
}
