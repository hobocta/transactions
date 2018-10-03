<?php

namespace Hobocta\Transactions\Authorization;

use Hobocta\Transactions\Application;
use Hobocta\Transactions\CommonException;

class Authorization
{
    private $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @return bool
     * @throws CommonException
     */
    public function isAuthorized()
    {
        $userId = $this->application->session->getUserId();
        if (!$userId) {
            $userId = $this->application->cookie->getUserId();
        }
        if (!$userId) {
            return false;
        }

        $hash = $this->application->session->getUserAuthHash();
        if (!$hash) {
            $hash = $this->application->cookie->getUserAuthHash();
        }
        if (!$hash) {
            return false;
        }

        $user = $this->application->users->getById($userId);
        if (!$user) {
            return false;
        }

        if ($user['auth_hash'] !== $hash) {
            return false;
        }

        return true;
    }

    /**
     * @param $userId
     * @throws CommonException
     */
    public function createHash($userId)
    {
        $userId = (int)$userId;

        if (empty($userId)) {
            throw new CommonException('Empty userId');
        }

        $hash = Hash::generate();
        $this->application->users->updateAuthHash($userId, $hash);
        $this->application->session->setUserAuthHash($hash);
        $this->application->cookie->setUserAuthHash($hash);

        $this->application->session->setUserId($userId);
        $this->application->cookie->setUserId($userId);
    }
}
