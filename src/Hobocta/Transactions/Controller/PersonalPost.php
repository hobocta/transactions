<?php

namespace Hobocta\Transactions\Controller;

use Hobocta\Transactions\CommonException;
use Hobocta\Transactions\Template;

class PersonalPost extends AbstractController
{
    private $data;
    private $userData;

    /**
     * @throws CommonException
     */
    public function action()
    {
        $this->data = [];

        $this->userData = $this->authorization->getUserData();

        $this->data['balance'] = $this->getBalance($this->userData['id']);

        $this->checkPostFields();

        $this->fillSumToWithdraw();

        $this->checkBalanceFromForm();

        if (empty($this->data['errors'])) {
            $this->calculateBalanceNew();

            if (!$this->isConfirmed()) {
                $this->data['needConfirm'] = true;
            } else {
                $this->checkQueryRepeat();
                $this->withdrawDo();
            }
        }

        (new Template('personal', $this->data))->render();
    }

    /**
     * @param $userId
     * @return array
     * @throws CommonException
     */
    private function getBalance($userId)
    {
        $balance = $this->balance->getByUserId($userId);

        if (empty($balance)) {
            throw new CommonException('Unable to get balance');
        }

        return $balance;
    }

    private function checkPostFields()
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
    private function fillSumToWithdraw()
    {
        if (empty($this->data['errors'])) {
            $this->data['sumToWithdraw'] = $this->sum->unFormat($this->postData['sumToWithdraw']);

            if ($this->data['sumToWithdraw'] <= 0) {
                $this->data['errors'][] = 'Укажите корректную сумму для вывода средств';
            }

            if (empty($this->data['errors'])) {
                if ($this->data['sumToWithdraw'] > $this->data['balance']['balance']) {
                    $this->data['errors'][] = 'Недостаточно средств';
                }
            }

            $this->data['sumToWithdraw'];
        }
    }

    private function checkBalanceFromForm()
    {
        if (empty($this->data['errors'])) {
            if (!empty($this->postData['confirmed']) && $this->postData['balance'] !== $this->data['balance']['balance']) {
                unset($this->postData['confirmed']);
                $this->data['errors'][] = '
                    С момента последнего рассчёта предполагаемого нового баланса изменился исходный баланс,
                    поэтому введите сумму для вывода стредств еще раз
                ';
            }
        }
    }

    private function calculateBalanceNew()
    {
        $this->data['balanceNew'] = (int)$this->data['balance']['balance'] - $this->data['sumToWithdraw'];
        $this->data['balanceNewFormatted'] = $this->sum->format($this->data['balanceNew']);
    }

    private function isConfirmed()
    {
        return !empty($this->postData['confirmed']) && $this->postData['confirmed'] === 'true';
    }

    /**
     * @throws CommonException
     */
    private function checkQueryRepeat()
    {
        if ($this->session->getFormToken() === $this->postData['formToken']) {
            header('Refresh:0');
            die();
        }
    }

    /**
     * @throws CommonException
     */
    private function withdrawDo()
    {
        $this->session->setFormToken($this->postData['formToken']);
        $this->balanceManager->update($this->data['balance']['id'], $this->data['sumToWithdraw']);
        $this->data['balance'] = $this->balance->getByUserId($this->userData['id']);
        $this->data['updated'] = true;
        $this->data['messages'][] = 'Вывод средств выполнен';
    }
}
