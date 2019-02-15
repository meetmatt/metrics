<?php

namespace MeetMatt\Metrics\Server\Domain\Task;

use InvalidArgumentException;

final class Task
{
    /** @var string */
    private $id;

    /** @var string */
    private $listId;

    /** @var string */
    private $summary;

    /** @var bool */
    private $isDone;

    /** @var bool */
    private $isDeleted;

    /**
     * @param string $id
     * @param string $listId
     * @param string $summary
     * @param bool   $isDone
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $id, string $listId, string $summary, bool $isDone = false)
    {
        $summary = trim($summary);
        if ('' === $summary) {
            throw new InvalidArgumentException('Task summary must be at least 1 character long');
        }

        $this->id        = $id;
        $this->listId    = $listId;
        $this->summary   = $summary;
        $this->isDone    = $isDone;
        $this->isDeleted = false;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getListId(): string
    {
        return $this->listId;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function isDone(): bool
    {
        return $this->isDone;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    public function markAsDone(): void
    {
        $this->isDone = true;
    }

    public function markAsNotDone(): void
    {
        $this->isDone = false;
    }

    public function delete(): void
    {
        $this->isDeleted = true;
    }
}