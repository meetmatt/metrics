<?php

namespace MeetMatt\Metrics\Server\Domain\TaskList;

use InvalidArgumentException;
use MeetMatt\Metrics\Server\Domain\Identity\RandomIdGeneratorInterface;
use MeetMatt\Metrics\Server\Domain\Exception\AccessDeniedException;
use MeetMatt\Metrics\Server\Domain\Exception\NotFoundException;

class TaskListService
{
    /** @var RandomIdGeneratorInterface */
    private $randomIdGenerator;

    /** @var TaskListRepositoryInterface */
    private $taskListRepository;

    public function __construct(
        RandomIdGeneratorInterface $randomIdGenerator,
        TaskListRepositoryInterface $taskListRepository
    )
    {
        $this->randomIdGenerator = $randomIdGenerator;
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

    public function getUserTaskLists(int $userId): TaskListCollection
    {
        return $this->taskListRepository->findByUserId($userId);
    }

    /**
     * @param int    $userId
     * @param string $id
     *
     * @throws AccessDeniedException
     * @throws NotFoundException
     *
     * @return TaskList
     */
    public function getUserTaskList(int $userId, string $id): TaskList
    {
        $taskList = $this->taskListRepository->findById($id);
        if (null === $taskList) {
            throw new NotFoundException('Task list ' . $id . ' not found');
        }
        if ($taskList->getUserId() !== $userId) {
            throw new AccessDeniedException();
        }

        return $taskList;
    }

    /**
     * @param int    $userId
     * @param string $id
     *
     * @throws NotFoundException
     * @throws AccessDeniedException
     */
    public function delete(int $userId, string $id): void
    {
        $taskList = $this->taskListRepository->findById($id);

        if (null === $taskList) {
            throw new NotFoundException('Task list ' . $id . ' not found');
        }

        if ($taskList->getUserId() !== $userId) {
            throw new AccessDeniedException();
        }

        $taskList->delete();
        $this->taskListRepository->updateIsDeleted($taskList);
    }
}