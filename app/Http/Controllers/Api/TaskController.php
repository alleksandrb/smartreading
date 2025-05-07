<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\DTOs\TaskData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $taskService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $tasks = $this->taskService->getFilteredTasks(
            status: request('status'),
            dueDate: request('due_date')
        );

        return TaskResource::collection($tasks)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $taskData = TaskData::fromRequest($request->validated());
        $task = $this->taskService->create($taskData);

        return new TaskResource($task)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $taskId): JsonResponse
    {
        $task = $this->taskService->find($taskId);
        return new TaskResource($task)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, int $taskId): JsonResponse
    {
        $taskData = TaskData::fromRequest($request->validated());
        $task = $this->taskService->update($taskId, $taskData);

        return new TaskResource($task)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $taskId): JsonResponse
    {
        $this->taskService->delete($taskId);

        return response()->json('DELETED TASk', Response::HTTP_NO_CONTENT);
    }
}
