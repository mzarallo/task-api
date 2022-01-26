<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Stage\GetAllStages;
use App\Actions\Stage\GetStageById;
use App\Http\Resources\BoardResource;
use App\Http\Resources\StageResource;
use App\Models\Board;
use App\Models\Stage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StageController extends Controller
{
    public function all(int $boardId, GetAllStages $getAllStages): AnonymousResourceCollection
    {
        return StageResource::collection($getAllStages->run(boardId: $boardId, relations: ['author']));
    }

    public function getById(int $boardId, int $stageId, GetStageById $getStageById): StageResource|JsonResponse
    {
        try {

            return new StageResource($getStageById->run(stageId: $stageId, belongToBoardId: $boardId, relations: ['author']));
        }catch (ModelNotFoundException) {
            return response()->json(['message' => 'Stage not found'], 404);
        }
    }
}
