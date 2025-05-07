<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\DTOs\TaskData;
use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Services\TaskService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class TaskServiceTest extends MockeryTestCase
{
    private TaskService $taskService;
    private TaskRepositoryInterface $taskRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskRepository = Mockery::mock(TaskRepositoryInterface::class);
        $this->taskService = new TaskService($this->taskRepository);
    }

    public function test_create_task_successfully(): void
    {
        // Arrange
        $taskData = new TaskData(
            title: 'Test Task',
            description: 'Test Description',
            status: 'pending',
            dueDate: Carbon::now()->addDays(7),
            userId: 1
        );

        $expectedTask = Mockery::mock(Task::class);
        $expectedTask->shouldReceive('getAttribute')->with('title')->andReturn($taskData->title);
        $expectedTask->shouldReceive('getAttribute')->with('description')->andReturn($taskData->description);
        $expectedTask->shouldReceive('getAttribute')->with('status')->andReturn($taskData->status);

        $this->taskRepository
            ->shouldReceive('create')
            ->once()
            ->with($taskData)
            ->andReturn($expectedTask);

        // Act
        $result = $this->taskService->create($taskData);

        // Assert
        $this->assertInstanceOf(Task::class, $result);
        $this->assertEquals($taskData->title, $result->title);
        $this->assertEquals($taskData->description, $result->description);
        $this->assertEquals($taskData->status, $result->status);
    }

    public function test_update_task_successfully(): void
    {
        // Arrange
        $taskId = 1;
        $taskData = new TaskData(
            title: 'Updated Task',
            description: 'Updated Description',
            status: 'completed',
            dueDate: Carbon::now()->addDays(7),
            userId: 1
        );

        $existingTask = Mockery::mock(Task::class);
        $existingTask->shouldReceive('getAttribute')->with('id')->andReturn($taskId);

        $updatedTask = Mockery::mock(Task::class);
        $updatedTask->shouldReceive('getAttribute')->with('title')->andReturn($taskData->title);
        $updatedTask->shouldReceive('getAttribute')->with('description')->andReturn($taskData->description);
        $updatedTask->shouldReceive('getAttribute')->with('status')->andReturn($taskData->status);

        $this->taskRepository
            ->shouldReceive('find')
            ->once()
            ->with($taskId)
            ->andReturn($existingTask);

        $this->taskRepository
            ->shouldReceive('update')
            ->once()
            ->with($existingTask, $taskData)
            ->andReturn($updatedTask);

        // Act
        $result = $this->taskService->update($taskId, $taskData);

        // Assert
        $this->assertInstanceOf(Task::class, $result);
        $this->assertEquals($taskData->title, $result->title);
        $this->assertEquals($taskData->description, $result->description);
        $this->assertEquals($taskData->status, $result->status);
    }

    public function test_delete_task_successfully(): void
    {
        // Arrange
        $taskId = 1;
        $task = Mockery::mock(Task::class);
        $task->shouldReceive('getAttribute')->with('id')->andReturn($taskId);

        $this->taskRepository
            ->shouldReceive('find')
            ->once()
            ->with($taskId)
            ->andReturn($task);

        $this->taskRepository
            ->shouldReceive('delete')
            ->once()
            ->with($task);

        // Act & Assert
        $this->taskService->delete($taskId);
    }

    public function test_find_task_successfully(): void
    {
        // Arrange
        $taskId = 1;
        $expectedTask = Mockery::mock(Task::class);
        $expectedTask->shouldReceive('getAttribute')->with('id')->andReturn($taskId);

        $this->taskRepository
            ->shouldReceive('find')
            ->once()
            ->with($taskId)
            ->andReturn($expectedTask);

        // Act
        $result = $this->taskService->find($taskId);

        // Assert
        $this->assertInstanceOf(Task::class, $result);
        $this->assertEquals($taskId, $result->id);
    }

    public function test_get_filtered_tasks_successfully(): void
    {
        // Arrange
        $status = 'pending';
        $dueDate = '2024-05-01';
        $expectedTasks = new Collection([
            Mockery::mock(Task::class),
            Mockery::mock(Task::class),
        ]);

        $this->taskRepository
            ->shouldReceive('getFiltered')
            ->once()
            ->with($status, $dueDate)
            ->andReturn($expectedTasks);

        // Act
        $result = $this->taskService->getFilteredTasks($status, $dueDate);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
    }
} 