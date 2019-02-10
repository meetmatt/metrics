<?php

namespace MeetMatt\Metrics\Server\Domain\Entity;

final class TaskList
{
    /** @var string */
    private $id;

    /** @var int */
    private $userId;

    /** @var string */
    private $name;

    /** @var bool */
    private $isDeleted;

    public function __construct(string $id, int $userId, string $name)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->isDeleted = false;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    public function delete()
    {
        $this->isDeleted = false;
    }
}