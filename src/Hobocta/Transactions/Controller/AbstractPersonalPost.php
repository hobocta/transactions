<?php

namespace Hobocta\Transactions\Controller;

use Hobocta\Transactions\CommonException;

abstract class AbstractPersonalPost extends AbstractController
{
    protected $data;
    protected $userData;

    protected function fillUserData()
    {
        $this->userData = $this->authorization->getUserData();
    }

    /**
     * @param $userId
     * @throws CommonException
     */
    protected function fillBalance($userId)
    {
        $balance = $this->balance->getByUserId($userId);

        if (empty($balance)) {
            throw new CommonException('Unable to get balance');
        }

        $this->data['balance'] = $balance;
    }

    /**
     * @param $userId
     * @throws CommonException
     */
    protected function fillBalanceForUpdate($userId)
    {
        $balance = $this->balance->getByUserIdForUpdate($userId);

        if (empty($balance)) {
            throw new CommonException('Unable to get balance');
        }

        $this->data['balance'] = $balance;
    }

    protected function validatePostFields()
    {
        if (empty($this->postData['sumToWithdraw'])) {
            $this->data['errors'][] = 'Укажие сумму для вывода средств';
        }

        if (!$this->sum->isValidToUnFormat($this->postData['sumToWithdraw'])) {
            $this->data['errors'][] = 'Некорректный формат данных';
        }

        if (empty($this->postData['formToken'])) {
            $this->data['errors'][] = 'Empty formToken';
        }
    }

    /**
     * @throws CommonException
     */
    protected function fillSumToWithdraw()
    {
        if (empty($this->data['errors'])) {
            $this->data['sumToWithdraw'] = $this->sum->unFormat($this->postData['sumToWithdraw']);

            if ($this->data['sumToWithdraw'] <= 0) {
                $this->data['errors'][] = 'Укажите корректную сумму для вывода средств';
            } elseif ($this->data['sumToWithdraw'] > $this->data['balance']['balance']) {
                $this->data['errors'][] = 'Недостаточно средств';
            }
        }
    }

    /**
     * @throws CommonException
     */
    protected function calculateBalanceNew()
    {
        $this->data['balanceNew'] = $this->data['balance']['balance'] - $this->data['sumToWithdraw'];

        if ($this->data['balanceNew'] < 0) {
            throw new CommonException('Incorrect sum');
        }

        $this->data['balanceNewFormatted'] = $this->sum->format($this->data['balanceNew']);
    }
}
