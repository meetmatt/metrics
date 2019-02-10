<?php

namespace MeetMatt\Metrics\Server\Domain\Entity;

class TaskListCollection
{
    /** @var TaskList[] */
    private $taskLists;

    /**
     * @param TaskList[] $taskLists
     */
    public function __construct(array $taskLists = [])
    {
        $this->taskLists = $taskLists;
    }

    public function add(TaskList $taskList): void
    {
        $this->taskLists[] = $taskList;
    }

    /**
     * @return TaskList[]
     */
    public function getAll(): array
    {
        return $this->taskLists;
    }
}