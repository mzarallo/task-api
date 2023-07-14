<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Tasks\GetAllTasksService;
use App\Data\Services\Tasks\GetAllTaskServiceDto;
use App\Http\Resources\TaskResource;
use App\Models\Board;
use App\Models\Stage;
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
}
