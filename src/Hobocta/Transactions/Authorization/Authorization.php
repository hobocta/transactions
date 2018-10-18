<?php

namespace Hobocta\Transactions\Authorization;

use Hobocta\Transactions\CommonException;
use Hobocta\Transactions\Database\Table\Users;

class Authorization
{
    private $userData = [];
    private $userId;
    private $session;
    private $cookie;
    private $users;

    /**
     * Authorization constructor.
     * @param Session $session
     * @param Cookie $cookie
     * @param Users $users
     */
    public function __construct(Session $session, Cookie $cookie, Users $users)
    {
        $this->session = $session;
        $this->cookie = $cookie;
        $this->users = $users;
    }

    /**
     * @return bool
     * @throws CommonException
     */
    public function isAuthorized(): bool
    {
        $this->userId = $this->session->getUserId();
        if (!$this->userId) {
            $this->userId = $this->cookie->getUserId();
        }
        if (!$this->userId) {
            return false;
        }

        $hash = $this->session->getUserAuthHash();
        if (!$hash) {
            $hash = $this->cookie->getUserAuthHash();
        }
        if (!$hash) {
            return false;
        }

        $this->userData = $this->users->getById($this->userId);
        if (!$this->userData) {
            return false;
        }

        if ($this->userData['auth_hash'] !== $hash) {
            return false;
        }

        return true;
    }

    /**
     * @param int $userId
     * @throws CommonException
     */
    public function createHash(int $userId)
    {
        if (empty($userId)) {
            throw new CommonException('Empty userId', ['userId' => $userId]);
        }

        $hash = Hash::generate();
        $this->users->updateAuthHash($userId, $hash);
        $this->session->setUserAuthHash($hash);
        $this->cookie->setUserAuthHash($hash);

        $this->session->setUserId($userId);
        $this->cookie->setUserId($userId);
    }

    /**
     * @throws CommonException
     */
    public function logout()
    {
        $this->session->setUserAuthHash('');
        $this->cookie->setUserAuthHash('');
        $this->session->setUserId(0);
        $this->cookie->setUserId(0);
    }

    /**
     * @return array
     */
    public function getUserData(): array
    {
        return $this->userData;
    }
}
