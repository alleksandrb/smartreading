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

    public function getFiltered(?string $status = null, ?string $dueDate = null, int $perPage = 15, int $page = 1): \Illuminate\Pagination\LengthAwarePaginator
    {
        $cacheKey = $this->getFilteredCacheKey($status, $dueDate, $perPage, $page);

        return Cache::remember(
            $cacheKey,
            self::CACHE_TTL,
            fn () => Task::query()
                ->when($status !== null, function (Builder $query) use ($status) {
                    $query->where('status', $status);
                })
                ->when($dueDate !== null, function (Builder $query) use ($dueDate) {
                    $query->whereDate('due_date', $dueDate);
                })
                ->with('user')
                ->paginate($perPage, ['*'], 'page', $page)
        );
    }

    private function getCacheKey(int $id): string
    {
        return self::CACHE_KEY_PREFIX . $id;
    }

    private function getFilteredCacheKey(?string $status, ?string $dueDate, int $perPage, int $page): string
    {
        return self::CACHE_KEY_FILTERED . ":{$status}:{$dueDate}:{$perPage}:{$page}";
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
