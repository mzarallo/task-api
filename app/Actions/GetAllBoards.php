<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Board;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAllBoards
{
    use AsAction;

    public function handle(array $relations): LengthAwarePaginator
    {
        return Board::with($relations)->orderBy('name')->paginate(20);
    }
}
