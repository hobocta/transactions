<?php

namespace Hobocta\Transactions\Authorization;

use Hobocta\Transactions\CommonException;

class Session
{
    const USER_ID = 'userId';
    const USER_AUTH_HASH = 'userAuthHash';
    const FORM_TOKEN = 'formToken';

    /**
     * @param int $id
     * @throws CommonException
     */
    public function setUserId(int $id)
    {
        $this->start();
        $_SESSION[static::USER_ID] = $id;
        $this->close();
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

    /**
     * @return int
     * @throws CommonException
     */
    public function getUserId(): int
    {
        $this->start();
        $result = isset($_SESSION[static::USER_ID]) ? (int)$_SESSION[static::USER_ID] : 0;
        $this->close();

        return $result;
    }

    /**
     * @param string $hash
     * @throws CommonException
     */
    public function setUserAuthHash(string $hash)
    {
        $this->start();
        $_SESSION[static::USER_AUTH_HASH] = $hash;
        $this->close();
    }

    /**
     * @return string
     * @throws CommonException
     */
    public function getUserAuthHash(): string
    {
        $this->start();
        $result = isset($_SESSION[static::USER_AUTH_HASH]) ? (string)$_SESSION[static::USER_AUTH_HASH] : '';
        $this->close();

        return $result;
    }

    /**
     * @param string $token
     * @throws CommonException
     */
    public function setFormToken(string $token)
    {
        $this->start();
        $_SESSION[static::FORM_TOKEN] = $token;
        $this->close();
    }

    /**
     * @return string
     * @throws CommonException
     */
    public function getFormToken(): string
    {
        $this->start();
        $result = isset($_SESSION[static::FORM_TOKEN]) ? (string)$_SESSION[static::FORM_TOKEN] : '';
        $this->close();

        return $result;
    }
}
