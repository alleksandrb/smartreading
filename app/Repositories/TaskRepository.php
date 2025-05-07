<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTOs\TaskData;
use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class TaskRepository implements TaskRepositoryInterface
{
    private const CACHE_TTL = 3600; // 1 hour
    private const CACHE_KEY_PREFIX = 'task:';
    private const CACHE_KEY_FILTERED = 'tasks:filtered:';

    public function find(int $id): Task
    {
        return Cache::remember(
            $this->getCacheKey($id),
            self::CACHE_TTL,
            fn () => Task::with('user')->findOrFail($id)
        );
    }

    public function create(TaskData $data): Task
    {
        $task = Task::create($data->toArray());
        $this->forgetFilteredCache();
        return $task;
    }

    public function update(Task $task, TaskData $data): Task
    {
        $task->update($data->toArray());
        $this->forgetCache($task->id);
        $this->forgetFilteredCache();
        return $task;
    }

    public function delete(Task $task): void
    {
        $task->delete();
        $this->forgetCache($task->id);
        $this->forgetFilteredCache();
    }

    public function getFiltered(?string $status = null, ?string $dueDate = null): Collection
    {
        $cacheKey = $this->getFilteredCacheKey($status, $dueDate);

        return Cache::remember(
            $cacheKey,
            self::CACHE_TTL,
            fn () => Task::query()
                ->when($status, function (Builder $query, string $status) {
                    $query->where('status', $status);
                })
                ->when($dueDate, function (Builder $query, string $dueDate) {
                    $query->whereDate('due_date', $dueDate);
                })
                ->with('user')
                ->get()
        );
    }

    private function getCacheKey(int $id): string
    {
        return self::CACHE_KEY_PREFIX . $id;
    }

    private function getFilteredCacheKey(?string $status, ?string $dueDate): string
    {
        return self::CACHE_KEY_FILTERED . md5($status . $dueDate);
    }

    private function forgetCache(int $id): void
    {
        Cache::forget($this->getCacheKey($id));
    }

    private function forgetFilteredCache(): void
    {
        Cache::forget(self::CACHE_KEY_FILTERED . '*');
    }
}
