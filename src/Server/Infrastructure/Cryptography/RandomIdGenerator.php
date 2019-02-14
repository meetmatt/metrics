<?php

namespace MeetMatt\Metrics\Server\Infrastructure\Cryptography;

use MeetMatt\Metrics\Server\Domain\Identity\RandomIdGeneratorInterface;
use Ramsey\Uuid\Uuid;

class RandomIdGenerator implements RandomIdGeneratorInterface
{
    public function generate(): string
    {
        return Uuid::uuid4();
    }
}