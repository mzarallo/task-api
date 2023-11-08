<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Tasks\CreateTaskService;
use App\Actions\Tasks\DeleteTaskById;
use App\Actions\Tasks\GetAllTasksService;
use App\Actions\Tasks\UpdateTaskById;
use App\Data\Services\Tasks\CreateTaskServiceDto;
use App\Data\Services\Tasks\DeleteTaskServiceDto;
use App\Data\Services\Tasks\GetAllTaskServiceDto;
use App\Data\Services\Tasks\UpdateTaskServiceDto;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Board;
use App\Models\Stage;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class TasksController extends Controller
{
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

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function updateById(
        UpdateTaskRequest $request,
        Board $board,
        Stage $stage,
        Task $task,
        UpdateTaskById $updateTaskById
    ): JsonResponse {

        return response()->json(
            new TaskResource(
                $updateTaskById->handle($task, UpdateTaskServiceDto::from($request->validated()))
            ),
            Response::HTTP_OK
        );
    }

    public function all(Board $board, Stage $stage, GetAllTasksService $tasksService): AnonymousResourceCollection
    {
        return TaskResource::collection(
            $tasksService->handle(
                GetAllTaskServiceDto::from(['board' => $board->id, 'stage' => $stage->id, 'relations' => ['author']])
            )
        );
    }
}
