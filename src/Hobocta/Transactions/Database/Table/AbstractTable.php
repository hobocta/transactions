<?php

namespace Hobocta\Transactions\Database\Table;

use Hobocta\Transactions\Database\Database;
use Hobocta\Transactions\Sum;

class AbstractTable
{
    protected $database;
    protected $sum;

    public function __construct(Database $database, Sum $sum)
    {
        $this->database = $database;
        $this->sum = $sum;
    }
}
