<?php

declare(strict_types=1);

namespace App\Actions\Boards;

use App\Data\Services\Boards\GetAllBoardsServiceDto;
use App\Models\Board;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\LaravelData\Optional;

class GetAllBoards
{
    use AsAction;

    public function handle(GetAllBoardsServiceDto $dto): LengthAwarePaginator|Collection
    {
        $boards = Board::query()
            ->with($dto->relations instanceof Optional ? [] : $dto->relations)
            ->orderBy('name');

        return $dto->paginated ? $boards->paginate(15) : $boards->get();
    }
}
