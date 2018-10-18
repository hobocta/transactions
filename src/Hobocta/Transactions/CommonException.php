<?php

namespace Hobocta\Transactions;

class CommonException extends \Exception
{
    public $data;

    public function __construct(string $message = '', array $data = [])
    {
        parent::__construct($message);

        $this->data = $data;
    }
}
