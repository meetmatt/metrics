<?php

namespace MeetMatt\Metrics\Server\Domain\Service;

use InvalidArgumentException;

interface PasswordHashingServiceInterface
{
    /**
     * @param string $plainTextPassword
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    public function hashPassword(string $plainTextPassword): string;

    public function isSamePasswordHash(string $knownHash, string $inputPlainTextPassword): bool;
}