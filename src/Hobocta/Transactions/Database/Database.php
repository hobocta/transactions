<?php

namespace Hobocta\Transactions\Database;

use Hobocta\Transactions\CommonException;

class Database
{
    /**
     * @var \PDO
     */
    public $pdo;

    public function __construct(array $config)
    {
        $this->pdo = new \PDO(
            sprintf(
                'mysql:dbname=%s;host=%s;port=%s',
                $config['dbname'],
                $config['host'],
                $config['port']
            ),
            $config['username'],
            $config['password']
        );
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
        $result = $this->query('Commit');
        if (!$result) {
            throw new CommonException('Unable to commit changes');
        }
    }

    /**
     * @throws CommonException
     */
    public function rollback()
    {
        $result = $this->query('Rollback');
        if (!$result) {
            throw new CommonException('Unable to rollback');
        }
    }
}
