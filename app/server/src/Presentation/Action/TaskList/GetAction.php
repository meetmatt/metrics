<?php

namespace MeetMatt\Metrics\Server\Presentation\Action\TaskList;

use MeetMatt\Metrics\Server\Domain\Entity\TaskList;
use MeetMatt\Metrics\Server\Domain\Repository\TokenRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\Repository\TaskListRepositoryInterface;
use MeetMatt\Metrics\Server\Presentation\Action\User\ActionAbstract;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GetAction extends ActionAbstract
{
    /** @var TokenRepositoryInterface */
    private $tokenRepository;

    /** @var TaskListRepositoryInterface */
    private $taskListRepository;

    public function __construct(
        TokenRepositoryInterface $tokenRepository,
        TaskListRepositoryInterface $taskListRepository
    )
    {
        $this->tokenRepository = $tokenRepository;
        $this->taskListRepository = $taskListRepository;
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
        if ($taskList->getUserId() !== $userId) {
            return $this->accessDenied($response);
        }

        return $this->withJson(
            $response,
            [
                'id' => $taskList->getId(),
                'name' => $taskList->getName(),
            ]
        );
    }
}