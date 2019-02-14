<?php

namespace MeetMatt\Metrics\Server\Infrastructure\Mysql;

use MeetMatt\Metrics\Server\Domain\Task\Task;
use MeetMatt\Metrics\Server\Domain\Task\TaskCollection;
use MeetMatt\Metrics\Server\Domain\Task\TaskRepositoryInterface;
use ParagonIE\EasyDB\EasyDB;

class TaskRepository implements TaskRepositoryInterface
{
    /** @var EasyDB */
    private $db;

    public function __construct(EasyDB $db)
    {
        $this->db = $db;
    }

    public function findByListId(string $listId): TaskCollection
    {
        $tasks = new TaskCollection();

        $rows = $this->db->run('SELECT * FROM `task` WHERE `list_id` = ? AND `is_deleted` = 0', $listId);
        foreach ($rows as $row) {
            $tasks->add(
                new Task(
                    $row['id'],
                    $row['list_id'],
                    $row['summary'],
                    (bool)$row['is_done']
                )
            );
        }

        return $tasks;
    }

    public function add(Task $task): void
    {
        $this->db->insert(
            'task',
            [
                'id' => $task->getId(),
                'list_id' => $task->getListId(),
                'summary' => $task->getSummary(),
            ]
        );
    }

    public function findById(string $id): ?Task
    {
        $result = $this->db->row('SELECT * FROM `task` WHERE `id` = ? AND `is_deleted` = 0', $id);
        if (empty($result)) {
            return null;
        }

        return new Task(
            $result['id'],
            $result['list_id'],
            $result['summary'],
            (bool)$result['is_done']
        );
    }

    public function updateIsDone(Task $task): void
    {
        $this->db->update(
            'task',
            [
                'is_done' => $task->isDone(),
            ],
            [
                'id' => $task->getId(),
            ]
        );
    }

    public function updateIsDeleted(Task $task): void
    {
        $this->db->update(
            'task',
            [
                'is_deleted' => $task->isDeleted(),
            ],
            [
                'id' => $task->getId(),
            ]
        );
    }
}