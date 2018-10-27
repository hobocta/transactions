<?php

namespace Hobocta\Transactions\Controller;

use Hobocta\Transactions\Authorization\Authorization;
use Hobocta\Transactions\Authorization\Session;
use Hobocta\Transactions\BalanceManager;
use Hobocta\Transactions\Database\Database;
use Hobocta\Transactions\Database\Table;
use Hobocta\Transactions\Sum;
use Symfony\Component\HttpFoundation\Request;

class AbstractController
{
    protected $authorization;
    protected $session;
    protected $database;
    protected $users;
    protected $balance;
    protected $balanceManager;
    protected $sum;
    protected $getData = [];
    protected $postData = [];

    public function __construct(
        Request $request,
        Authorization $authorization,
        Session $session,
        Database $database,
        Table\Users $users,
        Table\Balance $balance,
        BalanceManager $balanceManager,
        Sum $sum
    ) {
        $this->authorization = $authorization;
        $this->session = $session;
        $this->database = $database;
        $this->users = $users;
        $this->balance = $balance;
        $this->balanceManager = $balanceManager;
        $this->sum = $sum;
        $this->getData = $request->query->all();
        $this->postData = $request->request->all();
    }
}
