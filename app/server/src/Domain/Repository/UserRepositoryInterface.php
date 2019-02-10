<?php

namespace MeetMatt\Metrics\Server\Domain\Repository;

use MeetMatt\Metrics\Server\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function add(User $user): void;

    public function findByUsername(string $username): ?User;
}