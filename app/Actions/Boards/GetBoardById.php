<?php

declare(strict_types=1);

namespace App\Actions\Boards;

use App\Data\Services\Boards\GetBoardByIdServiceDto;
use App\Models\Board;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\LaravelData\Optional;

class GetBoardById
{
    use AsAction;

    public function handle(GetBoardByIdServiceDto $dto): Model|Board
    {
        return Board::query()
            ->when(
                ! $dto->relations instanceof Optional,
                fn (Builder $builder) => $builder->with($dto->relations)
            )->when(
                ! $dto->where_clause instanceof Optional,
                fn (Builder $builder) => $builder->where($dto->where_clause)
            )->findOrFail($dto->board_id);
    }
}
