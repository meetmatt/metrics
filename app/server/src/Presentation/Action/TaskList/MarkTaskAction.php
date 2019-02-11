<?php

namespace MeetMatt\Metrics\Server\Presentation\Action\TaskList;

use InvalidArgumentException;
use MeetMatt\Metrics\Server\Domain\Repository\TaskListRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\Repository\TaskRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\Repository\TokenRepositoryInterface;
use MeetMatt\Metrics\Server\Presentation\Action\User\ActionAbstract;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class MarkTaskAction extends ActionAbstract
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

        $taskList = $this->taskListRepository->findById($arguments['list_id']);
        if (null === $taskList) {
            return $this->notFound($response);
        }
        if ($taskList->getUserId() !== $userId) {
            return $this->accessDenied($response);
        }

        $task = $this->taskRepository->findById($arguments['task_id']);
        if (null === $task) {
            return $this->notFound($response);
        }
        if ($task->getListId() !== $taskList->getId()) {
            return $this->notFound($response);
        }

        $body = $this->getJsonBody($request);
        if (!is_bool($body['is_done'])) {
            return $this->badRequest(
                $response,
                new InvalidArgumentException('Parameter `is_done` must be boolean')
            );
        }

        if ($body['is_done']) {
            $task->markAsDone();
        } else {
            $task->markAsNotDone();
        }
        $this->taskRepository->updateIsDone($task);

        return $response->withStatus(204);
    }
}