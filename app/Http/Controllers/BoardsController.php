<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Boards\CreateBoard;
use App\Actions\Boards\DeleteBoardById;
use App\Actions\Boards\GetAllBoards;
use App\Actions\Boards\GetBoardById;
use App\Actions\Boards\UpdateBoardById;
use App\Http\Requests\CreateBoardRequest;
use App\Http\Requests\UpdateBoardRequest;
use App\Http\Resources\BoardResource;
use App\Models\Board;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BoardsController extends Controller
{
    public function all(GetAllBoards $getAllBoards): AnonymousResourceCollection
    {
        return BoardResource::collection($getAllBoards->run(['author']));
    }

    public function getById(int $boardId, GetBoardById $getBoardById): BoardResource|JsonResponse
    {
        try {
            return new BoardResource($getBoardById->run($boardId));
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Board not found'], 404);
        }
    }

    public function deleteById(Board $board, DeleteBoardById $deleteBoardById): JsonResponse
    {
        $deleteBoardById->run($board->id);

        return response()->json([], 204);
    }

    public function updateById(int $boardId, UpdateBoardRequest $request, UpdateBoardById $updateBoardById): JsonResponse
    {
        try {
            $boardResource = new BoardResource($updateBoardById->run($boardId, $request->validated()));

            return response()->json($boardResource);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Board not found'], 404);
        }
    }

    public function create(CreateBoardRequest $request, CreateBoard $createBoard): JsonResponse
    {
        $boardResource = new BoardResource($createBoard->run($request->validated()));

        return response()->json($boardResource, 201);
    }
}
