<?php

namespace MeetMatt\Metrics\Server\Infrastructure\Mysql;

use MeetMatt\Metrics\Server\Domain\Metrics\MetricsInterface;
use MeetMatt\Metrics\Server\Domain\TaskList\TaskList;
use MeetMatt\Metrics\Server\Domain\TaskList\TaskListCollection;
use MeetMatt\Metrics\Server\Domain\TaskList\TaskListRepositoryInterface;
use ParagonIE\EasyDB\EasyDB;

class TaskListRepository implements TaskListRepositoryInterface
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

    public function findByUserId(int $userId): TaskListCollection
    {
        $tags = ['repository' => 'task_list', 'action' => 'find_by_user_id'];
        $this->metrics->increment('api.repository.call', $tags);

        $rows = $this->metrics->timer(
            'api.repository.call',
            function () use ($userId) {
                return $this->db->run('SELECT * FROM `list` WHERE `user_id` = ? AND `is_deleted` = 0', $userId);
            },
            $tags
        );

        $taskLists = new TaskListCollection();
        foreach ($rows as $row) {
            $taskLists->add(
                new TaskList(
                    $row['id'],
                    $row['user_id'],
                    $row['name']
                )
            );
        }

        return $taskLists;
    }

    public function add(TaskList $taskList): void
    {
        $tags = ['repository' => 'task_list', 'action' => 'add'];
        $this->metrics->increment('api.repository.call', $tags);

        $this->metrics->timer(
            'api.repository.call',
            function () use ($taskList) {
                $this->db->insert(
                    'list',
                    [
                        'id'      => $taskList->getId(),
                        'user_id' => $taskList->getUserId(),
                        'name'    => $taskList->getName(),
                    ]
                );
            },
            $tags
        );
    }

    public function findById(string $id): ?TaskList
    {
        $tags = ['repository' => 'task_list', 'action' => 'find_by_id'];
        $this->metrics->increment('api.repository.call', $tags);

        $result = $this->metrics->timer(
            'api.repository.call',
            function () use ($id) {
                return $this->db->row('SELECT * FROM `list` WHERE `id` = ? AND `is_deleted` = 0', $id);
            },
            $tags
        );

        if (empty($result)) {
            return null;
        }

        return new TaskList(
            $result['id'],
            $result['user_id'],
            $result['name']
        );
    }

    public function updateIsDeleted(TaskList $taskList): void
    {
        $tags = ['repository' => 'task_list', 'action' => 'update_is_disabled'];
        $this->metrics->increment('api.repository.call', $tags);

        $this->metrics->timer(
            'api.repository.call',
            function () use ($taskList) {
                $this->db->update(
                    'list',
                    [
                        'is_deleted' => $taskList->isDeleted(),
                    ],
                    [
                        'id' => $taskList->getId(),
                    ]
                );
            },
            $tags
        );
    }
}