<?php

declare(strict_types=1);

namespace App\Actions\Boards;

use App\Models\Board;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteBoardById
{
    use AsAction;

    public function handle(int $boardId): bool
    {
        return Board::query()->findOrFail($boardId)->delete();
    }
}
