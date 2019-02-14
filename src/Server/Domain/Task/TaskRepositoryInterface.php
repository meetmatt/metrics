<?php

namespace MeetMatt\Metrics\Server\Domain\Task;

interface TaskRepositoryInterface
{
    public function findByListId(string $listId): TaskCollection;

    public function add(Task $task): void;

    public function findById(string $id): ?Task;

    public function updateIsDone(Task $task): void;

    public function updateIsDeleted(Task $task): void;
}