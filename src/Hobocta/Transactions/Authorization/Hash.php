<?php

namespace Hobocta\Transactions\Authorization;

class Hash
{
    public static function generate()
    {
        return base64_encode(openssl_random_pseudo_bytes(30));
    }
}
