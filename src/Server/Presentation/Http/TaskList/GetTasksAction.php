<?php

namespace MeetMatt\Metrics\Server\Presentation\Http\TaskList;

use MeetMatt\Metrics\Server\Domain\Task\Task;
use MeetMatt\Metrics\Server\Domain\Task\TaskService;
use MeetMatt\Metrics\Server\Presentation\Http\ActionAbstract;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class GetTasksAction extends ActionAbstract
{
    /** @var TaskService */
    private $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $arguments = []
    ): ResponseInterface
    {
        $userId = $this->getUserId($request);
        $taskListId = $arguments['id'];

        $tasks = $this->taskService->getTasks($userId, $taskListId);

        return $this->withJson(
            $response,
            array_map(
                function (Task $task) {
                    return [
                        'id' => $task->getId(),
                        'summary' => $task->getSummary(),
                        'is_done' => $task->isDone(),
                    ];
                },
                $tasks->getAll()
            )
        );
    }
}