<?php

namespace Hobocta\Transactions\Controller;

use Hobocta\Transactions\CommonException;
use Hobocta\Transactions\Sum;
use Hobocta\Transactions\Template;

class PersonalWithdrawPost extends AbstractController
{
    /**
     * @throws CommonException
     */
    public function action()
    {
        $data = [];

        $userData = $this->application->authorization->getUserData();
        $data['balance'] = $this->application->balance->getByUserId($userData['id']);
        if (empty($data['balance'])) {
            throw new CommonException('Unable to get balance');
        }

        if (empty($this->postData['withdraw'])) {
            $data['errors'][] = 'Empty sum';
        }

        if (empty($this->postData['formToken'])) {
            $data['errors'][] = 'Empty formToken';
        }

        if (empty($data['errors'])) {
            $withdraw = round($this->postData['withdraw'], 2);

            if ($withdraw <= 0) {
                $data['errors'][] = 'Incorrect sum';
            }
        }

        if (empty($data['errors']) && isset($withdraw)) {
            if ($withdraw > $data['balance']['balance']) {
                $data['errors'][] = 'Insufficient funds';
            }
        }

        if (empty($data['errors']) && isset($withdraw)) {
            if (!empty($this->postData['confirm']) && $this->postData['balance'] !== $data['balance']['balance']) {
                unset($this->postData['confirm']);
                $data['errors'][] = '
                    С момента последнего рассчёта предполагаемого нового баланса изменился исходный баланс,
                    поэтому введите сумму для вывода стредств еще раз
                ';
            }
        }

        if (empty($data['errors']) && isset($withdraw)) {
            $data['withdraw'] = $withdraw;
            $data['balanceNew'] = $data['balance']['balance'] - $data['withdraw'];
            $data['balanceNewFormatted'] = Sum::format($data['balanceNew']);

            if (empty($this->postData['confirm']) || $this->postData['confirm'] !== 'true') {
                $data['needConfirm'] = true;
            } else {
                // check resend form by F5
                $isNewRequest = $this->application->session->getFormToken() !== $this->postData['formToken'];

                if ($isNewRequest) {
                    $this->application->session->setFormToken($this->postData['formToken']);
                    $this->application->balanceManager->update($data['balance']['id'], $data['withdraw']);
                    $data['balance'] = $this->application->balance->getByUserId($userData['id']);
                    $data['updated'] = true;
                    $data['messages'][] = 'Вывод средств выполнен';
                } else {
                    header('Refresh:0');
                    die();
                }
            }
        }

        (new Template('personal', $data))->render();
    }
}
