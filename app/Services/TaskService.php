<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\TaskData;
use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository
    ) {}

    public function create(TaskData $data): Task
    {
        return $this->taskRepository->create($data);
    }

    public function update(int $taskId, TaskData $data): Task
    {
        $task = $this->taskRepository->find($taskId);
        return $this->taskRepository->update($task, $data);
    }

    public function delete(int $taskId): void
    {
        $task = $this->taskRepository->find($taskId);
        $this->taskRepository->delete($task);
    }

    public function find(int $taskId): Task
    {
        return $this->taskRepository->find($taskId);
    }

    public function getFilteredTasks(?string $status = null, ?string $dueDate = null): Collection
    {
        return $this->taskRepository->getFiltered($status, $dueDate);
    }
} 