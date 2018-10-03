<?php

namespace Hobocta\Transactions\Authorization;

use Hobocta\Transactions\CommonException;

class Session
{
    const USER_ID = 'userId';
    const USER_AUTH_HASH = 'userAuthHash';

    /**
     * @param $id
     * @throws CommonException
     */
    public function setUserId($id)
    {
        $this->start();
        $_SESSION[static::USER_ID] = $id;
        $this->close();
    }

    /**
     * @return int|null
     * @throws CommonException
     */
    public function getUserId()
    {
        $this->start();
        $result = isset($_SESSION[static::USER_ID]) ? (int)$_SESSION[static::USER_ID] : null;
        $this->close();

        return $result;
    }

    /**
     * @param $hash
     * @throws CommonException
     */
    public function setUserAuthHash($hash)
    {
        $this->start();
        $_SESSION[static::USER_AUTH_HASH] = $hash;
        $this->close();
    }

    /**
     * @return null|string
     * @throws CommonException
     */
    public function getUserAuthHash()
    {
        $this->start();
        $result = isset($_SESSION[static::USER_AUTH_HASH]) ? (string)$_SESSION[static::USER_AUTH_HASH] : null;
        $this->close();

        return $result;
    }

    /**
     * @throws CommonException
     */
    private function start()
    {
        if (!session_start()) {
            throw new CommonException('Unable to start session');
        }
    }

    private function close()
    {
        session_write_close();
    }
}
