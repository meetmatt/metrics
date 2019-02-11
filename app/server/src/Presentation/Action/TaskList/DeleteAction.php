<?php

namespace MeetMatt\Metrics\Server\Presentation\Action\TaskList;

use MeetMatt\Metrics\Server\Domain\Repository\TokenRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\Service\Exception\AccessDeniedException;
use MeetMatt\Metrics\Server\Domain\Service\Exception\TaskListNotFoundException;
use MeetMatt\Metrics\Server\Domain\Service\TaskListService;
use MeetMatt\Metrics\Server\Presentation\Action\User\ActionAbstract;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class DeleteAction extends ActionAbstract
{
    /** @var TokenRepositoryInterface */
    private $tokenRepository;

    /** @var TaskListService */
    private $taskListService;

    public function __construct(TokenRepositoryInterface $tokenRepository, TaskListService $taskListService)
    {
        $this->tokenRepository = $tokenRepository;
        $this->taskListService = $taskListService;
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

        try {
            $this->taskListService->delete($userId, $arguments['id']);
        } catch (TaskListNotFoundException $exception) {
            return $this->notFound($response);
        } catch (AccessDeniedException $exception) {
            return $this->accessDenied($response);
        }

        return $response->withStatus(204);
    }
}