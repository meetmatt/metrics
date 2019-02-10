<?php

namespace MeetMatt\Metrics\Server\Domain\Entity;

final class Token
{
    /** @var string */
    private $id;

    /** @var int */
    private $userId;

    public function __construct(string $id, int $userId)
    {
        $this->id = $id;
        $this->userId = $userId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}