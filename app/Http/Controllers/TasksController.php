<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Tasks\CreateTaskService;
use App\Actions\Tasks\DeleteTaskById;
use App\Actions\Tasks\GetAllTasksService;
use App\Data\Services\Tasks\CreateTaskServiceDto;
use App\Data\Services\Tasks\DeleteTaskServiceDto;
use App\Data\Services\Tasks\GetAllTaskServiceDto;
use App\Http\Requests\Tasks\CreateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Board;
use App\Models\Stage;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TasksController extends Controller
{
    public function all(Board $board, Stage $stage, GetAllTasksService $tasksService): AnonymousResourceCollection
    {
        return TaskResource::collection(
            $tasksService->handle(
                GetAllTaskServiceDto::from(['board' => $board->id, 'stage' => $stage->id, 'relations' => ['author']])
            )
        );
    }

    public function create(CreateTaskRequest $request, Board $board, Stage $stage, CreateTaskService $taskService): TaskResource
    {
        return new TaskResource(
            $taskService->handle(
                CreateTaskServiceDto::from([
                    ...$request->validated(),
                    'author_id' => auth()->user()->id,
                    'stage_id' => $stage->id,
                ])
            )
        );
    }

    public function deleteById(Board $board, Stage $stage, Task $task, DeleteTaskById $deleteTask): JsonResponse
    {
        $deleteTask->handle(DeleteTaskServiceDto::from(['taskId' => $task->id]));

        return response()->json([], 204);
    }
}
