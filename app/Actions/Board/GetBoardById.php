<?php

declare(strict_types=1);

namespace App\Actions\Board;

use App\Models\Board;
use Lorisleiva\Actions\Concerns\AsAction;

class GetBoardById
{
    use AsAction;

    public function handle(int $boardId): Board
    {
        return Board::findOrFail($boardId);
    }
}
