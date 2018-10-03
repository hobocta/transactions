<?php

namespace Hobocta\Transactions\Authorization;

use Hobocta\Transactions\Application;
use Hobocta\Transactions\CommonException;

class Authorization
{
    private $application;
    private $userData;
    private $userId;

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
        $this->userId = $this->application->session->getUserId();
        if (!$this->userId) {
            $this->userId = $this->application->cookie->getUserId();
        }
        if (!$this->userId) {
            return false;
        }

        $hash = $this->application->session->getUserAuthHash();
        if (!$hash) {
            $hash = $this->application->cookie->getUserAuthHash();
        }
        if (!$hash) {
            return false;
        }

        $this->userData = $this->application->users->getById($this->userId);
        if (!$this->userData) {
            return false;
        }

        if ($this->userData['auth_hash'] !== $hash) {
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

    /**
     * @throws CommonException
     */
    public function logout()
    {
        $this->application->session->setUserAuthHash('');
        $this->application->cookie->setUserAuthHash('');
        $this->application->session->setUserId(0);
        $this->application->cookie->setUserId(0);
    }

    public function getUserData()
    {
        return $this->userData;
    }
}
