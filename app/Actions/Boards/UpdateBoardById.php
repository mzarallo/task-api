<?php

declare(strict_types=1);

namespace App\Actions\Boards;

use App\Data\Services\Boards\GetBoardByIdServiceDto;
use App\Data\Services\Boards\UpdateBoardServiceDto;
use App\Models\Board;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateBoardById
{
    use AsAction;

    public function __construct(private GetBoardById $getBoardById)
    {
    }

    public function handle(int $boardId, UpdateBoardServiceDto $dto): Board
    {
        $board = $this->getBoardById->handle(GetBoardByIdServiceDto::validateAndCreate([
            'board_id' => $boardId,
        ]));

        return $this->updateBoard($board, $dto->toArray());
    }

    private function updateBoard(Board $board, array $attributes): Board
    {
        return tap($board)->update($attributes);
    }
}
