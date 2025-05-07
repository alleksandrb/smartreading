<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Models\Task;
use Carbon\Carbon;

class TaskData
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $status,
        public readonly Carbon $dueDate,
        public readonly ?int $userId = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'],
            status: $data['status'],
            dueDate: Carbon::parse($data['due_date']),
            userId: $data['user_id'] ?? null,
        );
    }

    public static function fromModel(Task $task): self
    {
        return new self(
            title: $task->title,
            description: $task->description,
            status: $task->status,
            dueDate: $task->due_date,
            userId: $task->user_id,
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'due_date' => $this->dueDate,
            'user_id' => $this->userId,
        ];
    }
} 