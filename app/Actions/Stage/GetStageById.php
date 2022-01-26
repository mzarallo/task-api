<?php

declare(strict_types=1);

namespace App\Actions\Stage;

use App\Models\Stage;
use Illuminate\Database\Eloquent\Builder;
use Lorisleiva\Actions\Concerns\AsAction;

class GetStageById
{
    use AsAction;

    public function handle(int $stageId, int $belongToBoardId = null, array $relations = []): Stage
    {
        return Stage::when($belongToBoardId , fn (Builder $query) => $query->where('board_id', $belongToBoardId))
            ->with($relations)
            ->findOrFail($stageId);
    }
}
