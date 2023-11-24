<?php

declare(strict_types=1);

namespace App\Actions\Boards;

use App\Data\Services\Boards\CreateBoardServiceDto;
use App\Models\Board;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateBoard
{
    use AsAction;

    public function handle(CreateBoardServiceDto $dto): Board|Model
    {
        return Board::query()->create($dto->toArray());
    }
}
