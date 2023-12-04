<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Stages\CreateStage;
use App\Actions\Stages\DeleteStageById;
use App\Actions\Stages\GetAllStages;
use App\Actions\Stages\GetStageById;
use App\Actions\Stages\UpdateStageById;
use App\Data\Services\Stages\CreateStageServiceDto;
use App\Data\Services\Stages\DeleteStageByIdServiceDto;
use App\Data\Services\Stages\GetAllStagesServiceDto;
use App\Data\Services\Stages\GetStageByIdServiceDto;
use App\Data\Services\Stages\UpdateStageByIdServiceDto;
use App\Http\Requests\Stages\CreateStageRequest;
use App\Http\Requests\Stages\UpdateStageRequest;
use App\Http\Resources\StageResource;
use App\Models\Board;
use App\Models\Stage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class StagesController extends Controller
{
    public function getById(Board $board, Stage $stage, GetStageById $getStageById): StageResource|JsonResponse
    {
        return new StageResource(
            $getStageById->handle(
                GetStageByIdServiceDto::validateAndCreate([
                    'stage_id' => $stage->id,
                    'where_clause' => ['board_id' => $board->id],
                    'relations' => ['author'],
                ])
            )
        );
    }

    public function deleteById(Board $board, Stage $stage, DeleteStageById $deleteStageById): JsonResponse
    {
        $deleteStageById->handle(
            DeleteStageByIdServiceDto::validateAndCreate([
                'stage_id' => $stage->id,
            ])
        );

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function updateById(
        UpdateStageRequest $request,
        Board $board,
        Stage $stage,
        UpdateStageById $updateStageById
    ): JsonResponse {
        $stageUpdated = new StageResource(
            $updateStageById->handle($stage, UpdateStageByIdServiceDto::validateAndCreate([
                ...$request->validated(),
                'board_id' => $board->id,
            ]))
        );

        return response()->json($stageUpdated);
    }

    public function all(Board $board, GetAllStages $getAllStages): AnonymousResourceCollection
    {

        return StageResource::collection(
            $getAllStages->handle(
                GetAllStagesServiceDto::validateAndCreate([
                    'board_id' => $board->id,
                    'relations' => ['author'],
                    'paginated' => true,
                ])
            )
        );
    }

    public function create(CreateStageRequest $request, Board $board, CreateStage $createStage): JsonResponse
    {
        $boardResource = new StageResource(
            $createStage->handle(
                CreateStageServiceDto::validateAndCreate([
                    ...$request->validated(),
                    'board_id' => $board->id,
                    'author_id' => auth()->id(),
                ])
            )
        );

        return response()->json($boardResource, 201);
    }
}
