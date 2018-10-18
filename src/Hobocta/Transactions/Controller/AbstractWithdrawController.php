<?php

namespace Hobocta\Transactions\Controller;

use Hobocta\Transactions\CommonException;

abstract class AbstractWithdrawController extends AbstractController
{
    protected $data = [];
    protected $userData = [];

    protected function fillUserData()
    {
        $this->userData = $this->authorization->getUserData();
    }

    /**
     * @throws CommonException
     */
    protected function fillBalance()
    {
        $balance = $this->balance->getByUserId($this->userData['id']);

        if (empty($balance)) {
            throw new CommonException('Unable to get balance', ['balance' => $balance]);
        }

        $this->data['balance'] = $balance;
    }

    /**
     * @throws CommonException
     */
    protected function fillBalanceForUpdate()
    {
        $balance = $this->balance->getByUserIdForUpdate($this->userData['id']);

        if (empty($balance)) {
            throw new CommonException('Unable to get balance', ['balance' => $balance]);
        }

        $this->data['balance'] = $balance;
    }

    protected function validatePostFields()
    {
        if (empty($this->postData['sumToWithdraw'])) {
            $this->data['errors'][] = 'Укажие сумму для вывода средств';
        } elseif (!$this->sum->isValidToUnFormat($this->postData['sumToWithdraw'])) {
            $this->data['errors'][] = 'Некорректный формат данных';
        } elseif (empty($this->postData['formToken'])) {
            $this->data['errors'][] = 'Empty formToken';
        }
    }

    /**
     * @throws CommonException
     */
    protected function fillSumToWithdraw()
    {
        if (empty($this->data['errors'])) {
            $sumToWithdrawRaw = $this->postData['sumToWithdraw'];

            $this->data['sumToWithdraw'] = $this->sum->unFormat($sumToWithdrawRaw);

            if (is_float($this->data['sumToWithdraw'])) {
                $this->data['errors'][] = 'Слишком большое значение';
            } elseif (!is_int($this->data['sumToWithdraw'])) {
                throw new CommonException(
                    'Некорректное значение',
                    ['sumToWithdrawRaw' => $sumToWithdrawRaw, 'sumToWithdraw' => $this->data['sumToWithdraw']]
                );
            }

            if (empty($this->data['errors'])) {
                if ($this->data['sumToWithdraw'] <= 0) {
                    $this->data['errors'][] = 'Укажите корректную сумму для вывода средств';
                } elseif ($this->data['sumToWithdraw'] > $this->data['balance']['balance']) {
                    $this->data['errors'][] = 'Недостаточно средств';
                }
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
            throw new CommonException(
                'New sum is negative',
                [
                    'balance' => $this->data['balance']['balance'],
                    'sumToWithdraw' => $this->data['sumToWithdraw'],
                    'balanceNew' => $this->data['balanceNew'],
                ]
            );
        }

        $this->data['balanceNewFormatted'] = $this->sum->format($this->data['balanceNew']);
    }
}
