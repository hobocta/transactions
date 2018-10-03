<?php

namespace Hobocta\Transactions;

class Sum
{
    public static function format($sum)
    {
        return number_format($sum, 2, ',', ' ');
    }
}
