<?php

declare(strict_types=1);

namespace App\Actions\Stages;

use App\Models\Stage;
use Illuminate\Database\Eloquent\Builder;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteStageById
{
    use AsAction;

    public function handle(int $stageId, array $whereClause = []): bool|null
    {
        return Stage::query()
            ->when($whereClause, fn (Builder $builder) => $builder->where($whereClause))
            ->findOrFail($stageId)
            ->delete();
    }
}
