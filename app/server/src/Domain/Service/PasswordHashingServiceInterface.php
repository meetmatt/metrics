<?php

namespace MeetMatt\Metrics\Server\Domain\Service;

interface PasswordHashingServiceInterface
{
    public function hashPassword(string $plainTextPassword): string;

    public function isSamePasswordHash(string $knownHash, string $inputPlainTextPassword): bool;
}