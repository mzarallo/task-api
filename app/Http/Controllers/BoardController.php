<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Board\GetAllBoards;
use App\Actions\Board\GetBoardById;
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
            return response()->json(['message' => 'User not found'], 404);
        }
    }
}
