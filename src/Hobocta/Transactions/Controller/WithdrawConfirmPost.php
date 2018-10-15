<?php

namespace Hobocta\Transactions\Controller;

use Hobocta\Transactions\CommonException;
use Hobocta\Transactions\Template;

class WithdrawConfirmPost extends AbstractPersonalPost
{
    /**
     * @throws CommonException
     */
    public function action()
    {
        $this->fillUserData();

        $this->database->startTransaction();

        $this->data = [];

        $this->fillBalanceForUpdate($this->userData['id']);

        $this->validatePostFields();

        $this->fillSumToWithdraw();

        $this->validateBalanceFromForm();

        if (empty($this->data['errors'])) {
            $this->validateFormToken();
            $this->calculateBalanceNew();
            $this->setFormToken();
            $this->withdrawDo();
            $this->data['balance'] = $this->balance->getByUserId($this->userData['id']);
        }

        if (empty($this->data['errors'])) {
            $this->database->commit();
        } else {
            $this->database->rollback();
        }

        if (!$this->data['updated']) {
            (new Template('withdrawConfirm', $this->data))->render();
        } else {
            (new Template('withdrawSuccess', $this->data))->render();
        }
    }

    private function validateBalanceFromForm()
    {
        if (empty($this->data['errors'])) {
            if ((int)$this->postData['balance'] !== $this->data['balance']['balance']) {
                header('Refresh:0');
                die();
            }
        }
    }

    /**
     * Предотвращает повторную обработку одинаковых запросов
     *
     * @throws CommonException
     */
    private function validateFormToken()
    {
        if ($this->session->getFormToken() === $this->postData['formToken']) {
            header('Refresh:0');
            die();
        }
    }

    /**
     * @throws CommonException
     */
    private function setFormToken()
    {
        $this->session->setFormToken($this->postData['formToken']);
    }

    /**
     * @throws CommonException
     */
    private function withdrawDo()
    {
        $this->balanceManager->update(
            $this->data['balance']['id'],
            $this->data['balance']['balance'],
            $this->data['balanceNew']
        );

        $this->data['updated'] = true;
        $this->data['messages'][] = 'Вывод средств выполнен';
    }
}
