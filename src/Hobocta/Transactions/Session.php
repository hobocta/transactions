<?php

namespace Hobocta\Transactions;

class Session
{
    /**
     * Session constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        if (!session_start()) {
            throw new \Exception('Unable to start session');
        }
    }
}
