<?php

namespace Hobocta\Transactions\Authorization;

class Cookie
{
    const USER_ID = 'userId';
    const USER_AUTH_HASH = 'userAuthHash';

    /**
     * @param string $hash
     */
    public function setUserId(string $hash)
    {
        setcookie(static::USER_ID, $hash, time() + 15 * 60);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return isset($_COOKIE[static::USER_ID]) ? (int)$_COOKIE[static::USER_ID] : 0;
    }

    /**
     * @param string $hash
     */
    public function setUserAuthHash(string $hash)
    {
        setcookie(static::USER_AUTH_HASH, $hash, time() + 15 * 60);
    }

    /**
     * @return string
     */
    public function getUserAuthHash(): string
    {
        return isset($_COOKIE[static::USER_AUTH_HASH]) ? (string)$_COOKIE[static::USER_AUTH_HASH] : '';
    }
}
