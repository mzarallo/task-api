<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Stages\CreateStage;
use App\Actions\Stages\DeleteStageById;
use App\Actions\Stages\GetAllStages;
use App\Actions\Stages\GetStageById;
use App\Actions\Stages\UpdateStageById;
use App\Http\Requests\CreateStageRequest;
use App\Http\Resources\StageResource;
use App\Models\Board;
use App\Models\Stage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StagesController extends Controller
{
    public function getById(Board $board, Stage $stage, GetStageById $getStageById): StageResource|JsonResponse
    {
        return new StageResource(
            $getStageById->handle(
                stageId: $stage->id,
                whereClause: ['board_id' => $board->id],
                relations: ['author']
            )
        );
    }

    public function deleteById(Board $board, Stage $stage, DeleteStageById $deleteStageById): JsonResponse
    {
        $deleteStageById->handle(stageId: $stage->id, whereClause: ['board_id' => $board->id]);

        return response()->json([], 204);
    }

    public function updateById(Request $request, Board $board, Stage $stage, UpdateStageById $updateStageById): JsonResponse
    {
        $stageUpdated = new StageResource($updateStageById->handle($stage, $request->all()));

        return response()->json($stageUpdated);
    }

    public function all(int $boardId, GetAllStages $getAllStages): AnonymousResourceCollection
    {
        return StageResource::collection($getAllStages->run(boardId: $boardId, relations: ['author']));
    }

    public function create(CreateStageRequest $request, CreateStage $createStage): JsonResponse
    {
        $boardResource = new StageResource($createStage->run($request->validated()));

        return response()->json($boardResource, 201);
    }
}
