<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Board\DeleteBoardById;
use App\Actions\Board\GetAllBoards;
use App\Actions\Board\GetBoardById;
use App\Actions\Board\UpdateBoardById;
use App\Http\Requests\UpdateBoardRequest;
use App\Http\Resources\BoardResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BoardController extends Controller
{
    public function all(GetAllBoards $getAllBoards): AnonymousResourceCollection
    {
        return BoardResource::collection($getAllBoards->run(['author']));
    }

    public function getById(int $boardId, GetBoardById $getBoardById): BoardResource | JsonResponse
    {
        try {
            return new BoardResource($getBoardById->run($boardId));
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Board not found'], 404);
        }
    }

    public function deleteById(int $boardId, DeleteBoardById $deleteBoardById): JsonResponse
    {
        try {
            $deleteBoardById->run($boardId);

            return response()->json([], 204);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Board not found'], 404);
        }
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
}
