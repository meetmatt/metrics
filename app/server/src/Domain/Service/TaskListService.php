<?php

namespace MeetMatt\Metrics\Server\Domain\Service;

use InvalidArgumentException;
use MeetMatt\Metrics\Server\Domain\Entity\TaskList;
use MeetMatt\Metrics\Server\Domain\Repository\TaskListRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\Service\Exception\AccessDeniedException;
use MeetMatt\Metrics\Server\Domain\Service\Exception\TaskListNotFoundException;

class TaskListService
{
    /** @var RandomIdGeneratorServiceInterface */
    private $randomIdGenerator;

    /** @var TaskListRepositoryInterface */
    private $taskListRepository;

    public function __construct(
        RandomIdGeneratorServiceInterface $randomIdGeneratorService,
        TaskListRepositoryInterface $taskListRepository
    )
    {
        $this->randomIdGenerator = $randomIdGeneratorService;
        $this->taskListRepository = $taskListRepository;
    }

    /**
     * @param int    $userId
     * @param string $name
     *
     * @throws InvalidArgumentException
     *
     * @return TaskList
     */
    public function create(int $userId, string $name): TaskList
    {
        $taskList = new TaskList(
            $this->randomIdGenerator->generate(),
            $userId,
            $name
        );

        $this->taskListRepository->add($taskList);

        return $taskList;
    }

    /**
     * @param int    $userId
     * @param string $id
     *
     * @throws TaskListNotFoundException
     * @throws AccessDeniedException
     */
    public function delete(int $userId, string $id): void
    {
        $taskList = $this->taskListRepository->findById($id);

        if (null === $taskList) {
            throw new TaskListNotFoundException();
        }

        if ($taskList->getUserId() !== $userId) {
            throw new AccessDeniedException();
        }

        $taskList->delete();
        $this->taskListRepository->updateIsDeleted($taskList);
    }
}