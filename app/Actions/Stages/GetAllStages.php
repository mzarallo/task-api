<?php

declare(strict_types=1);

namespace App\Actions\Stages;

use App\Models\Stage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAllStages
{
    use AsAction;

    public function handle(int $boardId = null, array $relations = [], $paginated = true): LengthAwarePaginator|Collection
    {
        $stages = Stage::query()
            ->with($relations)
            ->when($boardId, fn (Builder $query) => $query->where('board_id', $boardId))
            ->orderby('order');

        return $paginated ? $stages->paginate(15) : $stages->get();
    }
}
