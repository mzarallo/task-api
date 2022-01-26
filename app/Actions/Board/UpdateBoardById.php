<?php

declare(strict_types=1);

namespace App\Actions\Board;

use App\Models\Board;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateBoardById
{
    use AsAction;

    public function __construct(private GetBoardById $getBoardById)
    {
    }

    public function handle(int $boardId, array $attributes): Board
    {
        $board = $this->getBoardById->run($boardId);

        return $this->updateBoard($board, $attributes);
    }

    private function updateBoard(Board $board, array $attributes): Board
    {
        return tap($board)->update($attributes);
    }
}
