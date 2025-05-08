<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\DTOs\TaskData;
use App\Models\Task;

interface TaskRepositoryInterface
{
    public function find(int $id): Task;
    public function create(TaskData $data): Task;
    public function update(Task $task, TaskData $data): Task;
    public function delete(Task $task): void;
    public function getFiltered(?string $status = null, ?string $dueDate = null, int $perPage = 15, int $page = 1): \Illuminate\Pagination\LengthAwarePaginator;
} 