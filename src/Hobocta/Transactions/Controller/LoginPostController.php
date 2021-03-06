<?php

namespace Hobocta\Transactions\Controller;

use Hobocta\Transactions\CommonException;
use Hobocta\Transactions\Template;

class LoginPostController extends AbstractController
{
    /**
     * @throws CommonException
     */
    public function action()
    {
        $data = [];

        if (empty($this->postData['login'])) {
            $data['errors'][] = 'Укажите логин';
        } elseif (empty($this->postData['password'])) {
            $data['errors'][] = 'Укажите пароль';
        }

        if (empty($data['errors'])) {
            $user = $this->users->getByLogin($this->postData['login']);

            if ($user === false || !password_verify($this->postData['password'], $user['password_hash'])) {
                $data['errors'][] = 'Некорректная пара логин-пароль';
            }
        }

        if (!empty($data['errors'])) {
            (new Template('login', $data))->render();
        } elseif (!empty($user['id'])) {
            $this->authorization->createHash($user['id']);
            header('Refresh:0');
            die();
        } else {
            throw new CommonException('Unable to get user id', ['login' => $this->postData['login']]);
        }
    }
}
