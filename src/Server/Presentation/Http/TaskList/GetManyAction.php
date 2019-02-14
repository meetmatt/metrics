<?php

namespace MeetMatt\Metrics\Server\Presentation\Http\TaskList;

use MeetMatt\Metrics\Server\Domain\TaskList\TaskList;
use MeetMatt\Metrics\Server\Domain\TaskList\TaskListService;
use MeetMatt\Metrics\Server\Presentation\Http\ActionAbstract;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class GetManyAction extends ActionAbstract
{
    /** @var TaskListService */
    private $taskListService;

    public function __construct(TaskListService $taskListService)
    {
        $this->taskListService = $taskListService;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $arguments = []
    ): ResponseInterface
    {
        $taskLists = $this->taskListService->getUserTaskLists($this->getUserId($request));

        return $this->withJson(
            $response,
            array_map(
                function (TaskList $taskList) {
                    return [
                        'id' => $taskList->getId(),
                        'name' => $taskList->getName(),
                    ];
                },
                $taskLists->getAll()
            )
        );
    }
}