<?php

namespace Hobocta\Transactions\Controller;

use Hobocta\Transactions\Template;

class LoginPost extends AbstractController
{
    /**
     * @throws \Exception
     */
    public function action()
    {
        $data = [];

        if (empty($this->postData['login'])) {
            $data['errors'][] = 'Field login is not filled';
        }

        if (empty($this->postData['password'])) {
            $data['errors'][] = 'Field password is not filled';
        }

        if (empty($data['errors'])) {
            $user = $this->application->users->getByLogin($this->postData['login']);

            if ($user === false || !password_verify($this->postData['password'], $user['password_hash'])) {
                $data['errors'][] = 'Login or password is not correct';
            }
        }

        if (!empty($data['errors'])) {
            (new Template('login', $data))->render();
        } elseif (!empty($user['id'])) {
            $this->application->authorization->createHash($user['id']);
            header("Refresh:0");
            die();
        } else {
            throw new \Exception('Unable to get user id');
        }
    }
}
