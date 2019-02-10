<?php

namespace MeetMatt\Metrics\Server\Infrastructure\Mysql;

use MeetMatt\Metrics\Server\Domain\Entity\User;
use MeetMatt\Metrics\Server\Domain\Repository\UserRepositoryInterface;
use ParagonIE\EasyDB\EasyDB;

class UserRepository implements UserRepositoryInterface
{
    /** @var EasyDB */
    private $db;

    public function __construct(EasyDB $db)
    {
        $this->db = $db;
    }

    public function add(User $user): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $id = $this->db->insertReturnId('user', [
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
        ]);

        $user->setId($id);
    }

    public function findByUsername(string $username): ?User
    {
        $result = $this->db->row('SELECT * FROM `user` WHERE `username` = ?', $username);
        if (empty($result)) {
            return null;
        }

        $user = new User($result['username'], $result['password']);
        $user->setId((int)$result['id']);

        return $user;
    }
}