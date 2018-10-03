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

    /**
     * @throws CommonException
     */
    public function startTransaction()
    {
        $result = $this->query('Start transaction');
        if (!$result) {
            throw new CommonException('Unable to start transaction');
        }
    }

    /**
     * @throws CommonException
     */
    public function commit()
    {
        $result = $this->query("Commit");
        if (!$result) {
            throw new CommonException('Unable to commit changes');
        }
    }

    /**
     * @throws CommonException
     */
    public function rollback()
    {
        $result = $this->query("Rollback");
        if (!$result) {
            throw new CommonException('Unable to rollback');
        }
    }
}
