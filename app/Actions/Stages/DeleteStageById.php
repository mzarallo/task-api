<?php

declare(strict_types=1);

namespace App\Actions\Stages;

use App\Data\Services\Stages\DeleteStageByIdServiceDto;
use App\Models\Stage;
use Illuminate\Database\Eloquent\Builder;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteStageById
{
    use AsAction;

    public function handle(DeleteStageByIdServiceDto $dto): ?bool
    {
        return Stage::query()
            ->when($dto->where_clause, fn (Builder $query) => $query->where($dto->where_clause))
            ->findOrFail($dto->stage_id)
            ->delete();
    }
}
