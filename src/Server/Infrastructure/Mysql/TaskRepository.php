<?php

namespace MeetMatt\Metrics\Server\Infrastructure\Mysql;

use MeetMatt\Metrics\Server\Domain\Metrics\MetricsInterface;
use MeetMatt\Metrics\Server\Domain\Task\Task;
use MeetMatt\Metrics\Server\Domain\Task\TaskCollection;
use MeetMatt\Metrics\Server\Domain\Task\TaskRepositoryInterface;
use ParagonIE\EasyDB\EasyDB;

class TaskRepository implements TaskRepositoryInterface
{
    /** @var EasyDB */
    private $db;

    /** @var MetricsInterface */
    private $metrics;

    public function __construct(EasyDB $db, MetricsInterface $metrics)
    {
        $this->db      = $db;
        $this->metrics = $metrics;
    }

    public function findByListId(string $listId): TaskCollection
    {
        $tags = ['repository' => 'task', 'action' => 'find_by_list_id'];
        $this->metrics->increment('api.repository.call', $tags);

        $tasks = new TaskCollection();

        $rows = $this->metrics->timer(
            'api.repository.call',
            function () use ($listId) {
                return $this->db->run('SELECT * FROM `task` WHERE `list_id` = ? AND `is_deleted` = 0', $listId);
            },
            $tags
        );

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
        $tags = ['repository' => 'task', 'action' => 'add'];
        $this->metrics->increment('api.repository.call', $tags);

        $this->metrics->timer(
            'api.repository.call',
            function () use ($task) {
                $this->db->insert(
                    'task',
                    [
                        'id'      => $task->getId(),
                        'list_id' => $task->getListId(),
                        'summary' => $task->getSummary(),
                    ]
                );
            },
            $tags
        );
    }

    public function findById(string $id): ?Task
    {
        $tags = ['repository' => 'task', 'action' => 'find_by_id'];
        $this->metrics->increment('api.repository.call', $tags);

        $result = $this->metrics->timer(
            'api.repository.call',
            function () use ($id) {
                return $this->db->row('SELECT * FROM `task` WHERE `id` = ? AND `is_deleted` = 0', $id);
            },
            $tags
        );

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
        $tags = ['repository' => 'task', 'action' => 'update_is_done'];
        $this->metrics->increment('api.repository.call', $tags);

        $this->metrics->timer(
            'api.repository.call',
            function () use ($task) {
                $this->db->update(
                    'task',
                    [
                        'is_done' => $task->isDone(),
                    ],
                    [
                        'id' => $task->getId(),
                    ]
                );
            },
            $tags
        );
    }

    public function updateIsDeleted(Task $task): void
    {
        $tags = ['repository' => 'task', 'action' => 'update_is_deleted'];
        $this->metrics->increment('api.repository.call', $tags);

        $this->metrics->timer(
            'api.repository.call',
            function () use ($task) {
                $this->db->update(
                    'task',
                    [
                        'is_deleted' => $task->isDeleted(),
                    ],
                    [
                        'id' => $task->getId(),
                    ]
                );
            },
            $tags
        );
    }
}