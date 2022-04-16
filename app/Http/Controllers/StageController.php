<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Stage\DeleteStageById;
use App\Actions\Stage\GetAllStages;
use App\Actions\Stage\GetStageById;
use App\Http\Resources\StageResource;
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

    public function deleteById(int $boardId, int $stageId, DeleteStageById $deleteStageById): JsonResponse
    {
        try {
            $deleteStageById->run($stageId, ['board_id' => $boardId]);

            return response()->json([], 204);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Stage not found'], 404);
        }
    }
}
