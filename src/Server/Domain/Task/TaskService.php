<?php

namespace MeetMatt\Metrics\Server\Domain\Task;

use MeetMatt\Metrics\Server\Domain\Exception\AccessDeniedException;
use MeetMatt\Metrics\Server\Domain\Exception\NotFoundException;
use MeetMatt\Metrics\Server\Domain\Identity\RandomIdGeneratorInterface;
use MeetMatt\Metrics\Server\Domain\TaskList\TaskListService;

class TaskService
{
    /** @var TaskListService */
    private $taskListService;

    /** @var RandomIdGeneratorInterface */
    private $randomIdGenerator;

    /** @var TaskRepositoryInterface */
    private $taskRepository;

    public function __construct(
        TaskListService $taskListService,
        RandomIdGeneratorInterface $randomIdGenerator,
        TaskRepositoryInterface $taskRepository
    )
    {
        $this->taskListService = $taskListService;
        $this->randomIdGenerator = $randomIdGenerator;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param int    $userId
     * @param string $taskListId
     * @param string $summary
     *
     * @throws AccessDeniedException
     * @throws NotFoundException
     *
     * @return Task
     */
    public function create(int $userId, string $taskListId, string $summary): Task
    {
        $taskList = $this->taskListService->getUserTaskList($userId, $taskListId);

        $task = new Task(
            $this->randomIdGenerator->generate(),
            $taskList->getId(),
            $summary
        );

        $this->taskRepository->add($task);

        return $task;
    }

    /**
     * @param int    $userId
     * @param string $taskListId
     *
     * @throws AccessDeniedException
     * @throws NotFoundException
     *
     * @return TaskCollection
     */
    public function getTasks(int $userId, string $taskListId): TaskCollection
    {
        $taskList = $this->taskListService->getUserTaskList($userId, $taskListId);

        return $this->taskRepository->findByListId($taskList->getId());
    }

    /**
     * @param int    $userId
     * @param string $taskListId
     * @param string $taskId
     * @param bool   $isDone
     *
     * @throws AccessDeniedException
     * @throws NotFoundException
     */
    public function mark(int $userId, string $taskListId, string $taskId, bool $isDone): void
    {
        $task = $this->get($userId, $taskListId, $taskId);

        if ($isDone) {
            $task->markAsDone();
        } else {
            $task->markAsNotDone();
        }

        $this->taskRepository->updateIsDone($task);
    }

    /**
     * @param int    $userId
     * @param string $taskListId
     * @param string $taskId
     *
     * @throws AccessDeniedException
     * @throws NotFoundException
     */
    public function delete(int $userId, string $taskListId, string $taskId): void
    {
        $task = $this->get($userId, $taskListId, $taskId);

        $task->delete();
        $this->taskRepository->updateIsDeleted($task);
    }

    /**
     * @param int    $userId
     * @param string $taskListId
     * @param string $taskId
     *
     * @throws AccessDeniedException
     * @throws NotFoundException
     *
     * @return Task
     */
    private function get(int $userId, string $taskListId, string $taskId): Task
    {
        $taskList = $this->taskListService->getUserTaskList($userId, $taskListId);

        $task = $this->taskRepository->findById($taskId);
        if (null === $task || $task->getListId() !== $taskList->getId()) {
            throw new NotFoundException('Task ' . $taskId . ' not found');
        }

        return $task;
    }

}