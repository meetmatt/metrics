<?php

namespace MeetMatt\Metrics\Server\Infrastructure\Cryptography;

use MeetMatt\Metrics\Server\Domain\Service\RandomIdGeneratorServiceInterface;
use Ramsey\Uuid\Uuid;

class RandomIdGeneratorService implements RandomIdGeneratorServiceInterface
{
    public function generate(): string
    {
        return Uuid::uuid4();
    }
}