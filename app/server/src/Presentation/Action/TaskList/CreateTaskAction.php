<?php

namespace MeetMatt\Metrics\Server\Presentation\Action\TaskList;

use MeetMatt\Metrics\Server\Domain\Entity\Task;
use MeetMatt\Metrics\Server\Domain\Repository\TaskListRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\Repository\TaskRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\Repository\TokenRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\Service\RandomIdGeneratorServiceInterface;
use MeetMatt\Metrics\Server\Presentation\Action\User\ActionAbstract;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CreateTaskAction extends ActionAbstract
{
    /** @var TokenRepositoryInterface */
    private $tokenRepository;

    /** @var TaskListRepositoryInterface */
    private $taskListRepository;

    /** @var RandomIdGeneratorServiceInterface */
    private $randomIdGenerator;

    /** @var TaskRepositoryInterface */
    private $taskRepository;

    public function __construct(
        TokenRepositoryInterface $tokenRepository,
        TaskListRepositoryInterface $taskListRepository,
        RandomIdGeneratorServiceInterface $randomIdGenerator,
        TaskRepositoryInterface $taskRepository
    )
    {
        $this->tokenRepository = $tokenRepository;
        $this->taskListRepository = $taskListRepository;
        $this->randomIdGenerator = $randomIdGenerator;
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

        $body = $this->getJsonBody($request);

        $task = new Task(
            $this->randomIdGenerator->generate(),
            $taskList->getId(),
            $body['summary']
        );

        $this->taskRepository->add($task);

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