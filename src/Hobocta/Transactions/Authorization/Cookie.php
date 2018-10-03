<?php

namespace Hobocta\Transactions\Authorization;

class Cookie
{
    const USER_ID = 'userId';
    const USER_AUTH_HASH = 'userAuthHash';

    /**
     * @param $hash
     */
    public function setUserId($hash)
    {
        setcookie(static::USER_ID, $hash, time() + 15 * 60);
    }

    /**
     * @return int|null
     */
    public function getUserId()
    {
        return isset($_COOKIE[static::USER_ID]) ? (int)$_COOKIE[static::USER_ID] : null;
    }

    /**
     * @param $hash
     */
    public function setUserAuthHash($hash)
    {
        setcookie(static::USER_AUTH_HASH, $hash, time() + 15 * 60);
    }

    /**
     * @return null|string
     */
    public function getUserAuthHash()
    {
        return isset($_COOKIE[static::USER_AUTH_HASH]) ? (string)$_COOKIE[static::USER_AUTH_HASH] : null;
    }
}
