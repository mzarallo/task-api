<?php

declare(strict_types=1);

namespace App\Actions\Stages;

use App\Data\Services\Stages\GetStageByIdServiceDto;
use App\Models\Stage;
use Illuminate\Database\Eloquent\Builder;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\LaravelData\Optional;

class GetStageById
{
    use AsAction;

    public function handle(GetStageByIdServiceDto $dto): Stage
    {
        return Stage::query()
            ->when(! $dto->where_clause instanceof Optional, fn (Builder $query) => $query->where($dto->where_clause))
            ->with($dto->relations instanceof Optional ? [] : $dto->relations)
            ->findOrFail($dto->stage_id);
    }
}
