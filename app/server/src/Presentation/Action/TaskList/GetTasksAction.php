<?php

namespace MeetMatt\Metrics\Server\Presentation\Action\TaskList;

use MeetMatt\Metrics\Server\Domain\Entity\Task;
use MeetMatt\Metrics\Server\Domain\Repository\TaskListRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\Repository\TaskRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\Repository\TokenRepositoryInterface;
use MeetMatt\Metrics\Server\Presentation\Action\User\ActionAbstract;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GetTasksAction extends ActionAbstract
{
    /** @var TokenRepositoryInterface */
    private $tokenRepository;

    /** @var TaskListRepositoryInterface */
    private $taskListRepository;

    /** @var TaskRepositoryInterface */
    private $taskRepository;

    public function __construct(
        TokenRepositoryInterface $tokenRepository,
        TaskListRepositoryInterface $taskListRepository,
        TaskRepositoryInterface $taskRepository
    )
    {
        $this->tokenRepository = $tokenRepository;
        $this->taskListRepository = $taskListRepository;
        $this->taskRepository = $taskRepository;
    }

    public function __invoke(
        RequestInterface $request,
        ResponseInterface $response,
        array $arguments = []
    ): ResponseInterface
    {
        $userId = $this->getUserId($this->tokenRepository, $request);
        if (null === $userId) {
            return $this->unauthorized($response);
        }

        $taskList = $this->taskListRepository->findById($arguments['id']);
        if (null === $taskList) {
            return $this->notFound($response);
        }
        if ($taskList->getUserId() !== $userId) {
            return $this->accessDenied($response);
        }

        $tasks = $this->taskRepository->findByListId($taskList->getId());

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