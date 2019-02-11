<?php

namespace MeetMatt\Metrics\Server\Presentation\Action\TaskList;

use InvalidArgumentException;
use MeetMatt\Metrics\Server\Domain\Repository\TokenRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\Service\TaskListService;
use MeetMatt\Metrics\Server\Presentation\Action\User\ActionAbstract;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CreateAction extends ActionAbstract
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
        $body = $this->getJsonBody($request);

        try {
            $taskList = $this->taskListService->create($userId, $body['name']);
        } catch (InvalidArgumentException $exception) {
            return $this->badRequest($response, $exception);
        }

        return $this->withJson(
            $response,
            [
                'id' => $taskList->getId(),
                'name' => $taskList->getName(),
            ],
            201
        );
    }
}