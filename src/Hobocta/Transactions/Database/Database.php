<?php
namespace Hobocta\Transactions\Database;

use Hobocta\Transactions\CommonException;

class Database
{
    /**
     * @var \PDO
     */
    public $pdo;

    public function __construct()
    {
        $this->pdo = new \PDO('mysql:dbname=hobocta_transactions;host=127.0.0.1;port=3306', 'root', '');
    }

    /**
     * @param $query
     * @return \PDOStatement
     * @throws CommonException
     */
    public function query($query)
    {
        $result = $this->pdo->query($query);

        if ($result === false) {
            throw new CommonException(serialize($this->pdo->errorInfo()));
        }

        return $result;
    }
}
