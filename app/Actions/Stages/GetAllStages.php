<?php

declare(strict_types=1);

namespace App\Actions\Stages;

use App\Data\Services\Stages\GetAllStagesServiceDto;
use App\Models\Stage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\LaravelData\Optional;

class GetAllStages
{
    use AsAction;

    public function handle(GetAllStagesServiceDto $dto): LengthAwarePaginator|Collection
    {
        $stages = Stage::query()
            ->with($dto->relations instanceof Optional ? [] : $dto->relations)
            ->when($dto->board_id, fn (Builder $query) => $query->where('board_id', $dto->board_id))
            ->orderby('order');

        return $dto->paginated ? $stages->paginate(15) : $stages->get();
    }
}
