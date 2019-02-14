<?php

namespace MeetMatt\Metrics\Server\Presentation\Http\TaskList;

use MeetMatt\Metrics\Server\Domain\Task\TaskService;
use MeetMatt\Metrics\Server\Presentation\Http\ActionAbstract;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreateTaskAction extends ActionAbstract
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
        $taskListId = $arguments['id'];
        $userId = $this->getUserId($request);
        $body = $this->getJsonBody($request, ['summary']);

        $task = $this->taskService->create($userId, $taskListId, $body['summary']);

        return $this->withJson(
            $response,
            [
                'id' => $task->getId(),
                'summary' => $task->getSummary()
            ],
            201
        );
    }
}