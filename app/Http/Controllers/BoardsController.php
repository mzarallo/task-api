<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Boards\CreateBoard;
use App\Actions\Boards\DeleteBoardById;
use App\Actions\Boards\DownloadBoard;
use App\Actions\Boards\GetAllBoards;
use App\Actions\Boards\GetBoardById;
use App\Actions\Boards\UpdateBoardById;
use App\Data\Services\Boards\CreateBoardServiceDto;
use App\Data\Services\Boards\DeleteBoardByIdServiceDto;
use App\Data\Services\Boards\DownloadBoardServiceDto;
use App\Data\Services\Boards\GetAllBoardsServiceDto;
use App\Data\Services\Boards\GetBoardByIdServiceDto;
use App\Data\Services\Boards\UpdateBoardServiceDto;
use App\Http\Requests\Boards\CreateBoardRequest;
use App\Http\Requests\Boards\DownloadBoardRequest;
use App\Http\Requests\Boards\UpdateBoardRequest;
use App\Http\Resources\BoardResource;
use App\Models\Board;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class BoardsController extends Controller
{
    public function all(GetAllBoards $getAllBoards): AnonymousResourceCollection
    {
        return BoardResource::collection(
            $getAllBoards->handle(
                GetAllBoardsServiceDto::validateAndCreate([
                    'relations' => ['author'],
                    'paginated' => true,
                ])
            ),
        );
    }

    public function getById(Board $board, GetBoardById $getBoardById): BoardResource|JsonResponse
    {
        return new BoardResource($getBoardById->handle(
            GetBoardByIdServiceDto::validateAndCreate([
                'board_id' => $board->id,
            ]))
        );
    }

    public function deleteById(Board $board, DeleteBoardById $deleteBoardById): JsonResponse
    {
        $deleteBoardById->handle(DeleteBoardByIdServiceDto::validateAndCreate(['board_id' => $board->id]));

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function updateById(
        Board $board,
        UpdateBoardRequest $request,
        UpdateBoardById $updateBoardById
    ): JsonResponse {
        $boardResource = new BoardResource(
            $updateBoardById->handle(
                $board->id,
                UpdateBoardServiceDto::validateAndCreate($request->validated())
            )
        );

        return response()->json($boardResource);
    }

    public function create(CreateBoardRequest $request, CreateBoard $createBoard): JsonResponse
    {
        $boardResource = new BoardResource(
            $createBoard->handle(
                CreateBoardServiceDto::validateAndCreate([
                    ...$request->validated(),
                    'author_id' => auth()->id(),
                ])
            )
        );

        return response()->json($boardResource, Response::HTTP_CREATED);
    }

    public function download(DownloadBoardRequest $request, Board $board): JsonResponse
    {
        DownloadBoard::dispatch(
            DownloadBoardServiceDto::validateAndCreate([
                'user' => auth()->id(),
                'board' => $board->id,
                'format' => $request->get('format') ?? 'xls',
            ])
        );

        return response()->json(status: Response::HTTP_ACCEPTED);
    }
}
