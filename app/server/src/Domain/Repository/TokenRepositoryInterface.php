<?php

namespace MeetMatt\Metrics\Server\Domain\Repository;

use MeetMatt\Metrics\Server\Domain\Entity\Token;

interface TokenRepositoryInterface
{
    public function add(Token $token): void;

    public function find(string $id): ?Token;

    public function findAndRefresh(?string $id): ?Token;
}