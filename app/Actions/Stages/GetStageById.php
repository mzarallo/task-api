<?php

declare(strict_types=1);

namespace App\Actions\Stages;

use App\Models\Stage;
use Illuminate\Database\Eloquent\Builder;
use Lorisleiva\Actions\Concerns\AsAction;

class GetStageById
{
    use AsAction;

    public function handle(int $stageId, array $whereClause = [], array $relations = []): Stage
    {
        return Stage::query()
            ->when($whereClause, fn (Builder $query) => $query->where($whereClause))
            ->with($relations)
            ->findOrFail($stageId);
    }
}
