<?php

namespace MeetMatt\Metrics\Server\Domain\Repository;

use MeetMatt\Metrics\Server\Domain\Entity\TaskList;
use MeetMatt\Metrics\Server\Domain\Entity\TaskListCollection;

interface TaskListRepositoryInterface
{
    public function findByUserId(int $userId): TaskListCollection;

    public function add(TaskList $taskList): void;
}