<?php

namespace MeetMatt\Metrics\Server\Presentation\Http\TaskList;

use MeetMatt\Metrics\Server\Domain\TaskList\TaskListService;
use MeetMatt\Metrics\Server\Presentation\Http\ActionAbstract;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class GetAction extends ActionAbstract
{
    /** @var TaskListService */
    private $taskListService;

    public function __construct(TaskListService $getTaskListService)
    {
        $this->taskListService = $getTaskListService;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $arguments = []
    ): ResponseInterface
    {
        $userId = $this->getUserId($request);
        $id = $arguments['id'];

        $taskList = $this->taskListService->getUserTaskList($userId, $id);

        return $this->withJson(
            $response,
            [
                'id' => $taskList->getId(),
                'name' => $taskList->getName(),
            ]
        );
    }
}