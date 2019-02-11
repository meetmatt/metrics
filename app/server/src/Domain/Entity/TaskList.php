<?php

namespace MeetMatt\Metrics\Server\Domain\Entity;

use InvalidArgumentException;

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

    /**
     * @param string $id
     * @param int    $userId
     * @param string $name
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $id, int $userId, string $name)
    {
        $name = trim($name);
        if (strlen($name) < 1) {
            throw new InvalidArgumentException('Task list name must be at least 1 character long');
        }

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
        $this->isDeleted = true;
    }
}