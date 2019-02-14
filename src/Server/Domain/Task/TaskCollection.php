<?php

namespace MeetMatt\Metrics\Server\Domain\Task;

class TaskCollection
{
    /** @var Task[] */
    private $tasks;

    /**
     * @param Task[] $tasks
     */
    public function __construct(array $tasks = [])
    {
        $this->tasks = $tasks;
    }

    public function add(Task $task): void
    {
        $this->tasks[] = $task;
    }

    /**
     * @return Task[]
     */
    public function getAll(): array
    {
        return $this->tasks;
    }
}