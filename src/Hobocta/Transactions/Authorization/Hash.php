<?php

namespace Hobocta\Transactions\Authorization;

class Hash
{
    /**
     * @return string
     */
    public static function generate(): string
    {
        return base64_encode(openssl_random_pseudo_bytes(30));
    }
}
