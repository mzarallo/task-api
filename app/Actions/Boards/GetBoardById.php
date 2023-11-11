<?php

declare(strict_types=1);

namespace App\Actions\Boards;

use App\Models\Board;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class GetBoardById
{
    use AsAction;

    public function handle(int $boardId, array $relations = []): Model|Board
    {
        return Board::query()
            ->when($relations, fn (Builder $builder) => $builder->with($relations))
            ->findOrFail($boardId);
    }
}
