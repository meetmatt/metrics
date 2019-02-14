<?php

namespace MeetMatt\Metrics\Server\Presentation\Http\TaskList;

use MeetMatt\Metrics\Server\Domain\Task\TaskService;
use MeetMatt\Metrics\Server\Presentation\Http\ActionAbstract;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class MarkTaskAction extends ActionAbstract
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
        $taskListId = $arguments['list_id'];
        $taskId = $arguments['task_id'];
        $body = $this->getJsonBody($request, ['is_done']);
        $isDone = $body['is_done'];

        $this->taskService->mark($userId, $taskListId, $taskId, $isDone);

        return $response->withStatus(204);
    }
}