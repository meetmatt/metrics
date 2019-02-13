<?php

namespace MeetMatt\Metrics\Server\Domain\TaskList;

interface TaskListRepositoryInterface
{
    public function findByUserId(int $userId): TaskListCollection;

    public function add(TaskList $taskList): void;

    public function findById(string $id): ?TaskList;

    public function updateIsDeleted(TaskList $taskList): void;
}