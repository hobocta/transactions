<?php

namespace Hobocta\Transactions\Database\Table;

use Hobocta\Transactions\Database\Database;

class AbstractTable
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }
}
