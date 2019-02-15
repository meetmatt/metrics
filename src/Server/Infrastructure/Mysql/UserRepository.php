<?php

namespace MeetMatt\Metrics\Server\Infrastructure\Mysql;

use MeetMatt\Metrics\Server\Domain\Metrics\MetricsInterface;
use MeetMatt\Metrics\Server\Domain\User\User;
use MeetMatt\Metrics\Server\Domain\User\UserRepositoryInterface;
use ParagonIE\EasyDB\EasyDB;

class UserRepository implements UserRepositoryInterface
{
    /** @var EasyDB */
    private $db;

    /** @var MetricsInterface */
    private $metrics;

    public function __construct(EasyDB $db, MetricsInterface $metrics)
    {
        $this->db      = $db;
        $this->metrics = $metrics;
    }

    public function add(User $user): void
    {
        $tags = ['repository' => 'user', 'action' => 'add'];
        $this->metrics->increment('api.repository.call', $tags);

        $id = $this->metrics->timer(
            'api.repository.call',
            function () use ($user) {
                return $this->db->insertReturnId('user', [
                    'username' => $user->getUsername(),
                    'password' => $user->getPassword(),
                ]);
            },
            $tags
        );

        $user->setId($id);
    }

    public function findByUsername(string $username): ?User
    {
        $tags = ['repository' => 'user', 'action' => 'find_by_username'];
        $this->metrics->increment('api.repository.call', $tags);

        $result = $this->metrics->timer(
            'api.repository.call',
            function () use ($username) {
                return $this->db->row('SELECT * FROM `user` WHERE `username` = ?', $username);
            },
            $tags
        );

        if (empty($result)) {
            return null;
        }

        $user = new User($result['username'], $result['password']);
        $user->setId((int)$result['id']);

        return $user;
    }
}