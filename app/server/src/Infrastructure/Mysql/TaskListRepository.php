<?php

namespace MeetMatt\Metrics\Server\Infrastructure\Mysql;

use MeetMatt\Metrics\Server\Domain\Entity\TaskList;
use MeetMatt\Metrics\Server\Domain\Entity\TaskListCollection;
use MeetMatt\Metrics\Server\Domain\Repository\TaskListRepositoryInterface;
use ParagonIE\EasyDB\EasyDB;

class TaskListRepository implements TaskListRepositoryInterface
{
    /** @var EasyDB */
    private $db;

    public function __construct(EasyDB $db)
    {
        $this->db = $db;
    }

    public function findByUserId(int $userId): TaskListCollection
    {
        $taskLists = new TaskListCollection();
        $rows = $this->db->run('SELECT * FROM `list` WHERE `user_id` = ? AND `is_deleted` = 0', $userId);
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
        $this->db->insert(
            'list',
            [
                'id' => $taskList->getId(),
                'user_id' => $taskList->getUserId(),
                'name' => $taskList->getName(),
            ]
        );
    }
}