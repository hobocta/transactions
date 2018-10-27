<?php

namespace Hobocta\Transactions\Database;

use Hobocta\Transactions\CommonException;
use Hobocta\Transactions\Config;

class Database
{
    /**
     * @var \PDO
     */
    public $pdo;

    public function __construct(Config $config)
    {
        $databaseConfig = $config->get('database');

        $this->pdo = new \PDO(
            sprintf(
                'mysql:dbname=%s;host=%s;port=%s',
                $databaseConfig['dbname'],
                $databaseConfig['host'],
                $databaseConfig['port']
            ),
            $databaseConfig['username'],
            $databaseConfig['password']
        );
    }

    /**
     * @param string $query
     * @return \PDOStatement
     * @throws CommonException
     */
    public function query(string $query): \PDOStatement
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
            throw new CommonException('Unable to start transaction', ['result' => $result]);
        }
    }

    /**
     * @throws CommonException
     */
    public function commit()
    {
        $result = $this->query('Commit');
        if (!$result) {
            throw new CommonException('Unable to commit changes', ['result' => $result]);
        }
    }

    /**
     * @throws CommonException
     */
    public function rollback()
    {
        $result = $this->query('Rollback');
        if (!$result) {
            throw new CommonException('Unable to rollback', ['result' => $result]);
        }
    }
}
