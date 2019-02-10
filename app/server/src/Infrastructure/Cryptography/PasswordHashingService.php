<?php

namespace MeetMatt\Metrics\Server\Infrastructure\Cryptography;

use MeetMatt\Metrics\Server\Domain\Service\PasswordHashingServiceInterface;

class PasswordHashingService implements PasswordHashingServiceInterface
{
    public function hashPassword(string $plainTextPassword): string
    {
        return password_hash($plainTextPassword, PASSWORD_ARGON2ID);
    }

    public function isSamePasswordHash(string $knownHash, string $inputPlainTextPassword): bool
    {
        return password_verify($inputPlainTextPassword, $knownHash);
    }
}