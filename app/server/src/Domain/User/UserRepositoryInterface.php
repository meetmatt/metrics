<?php

namespace MeetMatt\Metrics\Server\Domain\User;

interface UserRepositoryInterface
{
    public function add(User $user): void;

    public function findByUsername(string $username): ?User;
}