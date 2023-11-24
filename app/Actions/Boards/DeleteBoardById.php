<?php

declare(strict_types=1);

namespace App\Actions\Boards;

use App\Data\Services\Boards\DeleteBoardByIdServiceDto;
use App\Models\Board;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteBoardById
{
    use AsAction;

    public function handle(DeleteBoardByIdServiceDto $dto): bool
    {
        return Board::query()->findOrFail($dto->board_id)->delete();
    }
}
