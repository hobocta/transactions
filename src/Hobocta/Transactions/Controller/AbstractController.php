<?php

namespace Hobocta\Transactions\Controller;

use Hobocta\Transactions\Authorization\Authorization;
use Hobocta\Transactions\Authorization\Session;
use Hobocta\Transactions\BalanceManager;
use Hobocta\Transactions\Database\Table;
use Hobocta\Transactions\Sum;

class AbstractController
{
    protected $authorization;
    protected $session;
    protected $users;
    protected $balance;
    protected $balanceManager;
    protected $sum;
    protected $getData;
    protected $postData;

    public function __construct(
        Authorization $authorization,
        Session $session,
        Table\Users $users,
        Table\Balance $balance,
        BalanceManager $balanceManager,
        Sum $sum,
        array $getData,
        array $postData
    ) {
        $this->authorization = $authorization;
        $this->session = $session;
        $this->users = $users;
        $this->balance = $balance;
        $this->balanceManager = $balanceManager;
        $this->sum = $sum;
        $this->getData = $getData;
        $this->postData = $postData;
    }
}
