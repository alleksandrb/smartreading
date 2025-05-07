<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\DTOs\TaskData;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    public function find(int $id): Task;
    public function create(TaskData $data): Task;
    public function update(Task $task, TaskData $data): Task;
    public function delete(Task $task): void;
    public function getFiltered(?string $status = null, ?string $dueDate = null): Collection;
} 