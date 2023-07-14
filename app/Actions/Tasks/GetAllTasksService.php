<?php

declare(strict_types=1);

namespace App\Actions\Tasks;

use App\Data\Services\Tasks\GetAllTaskServiceDto;
use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAllTasksService
{
    use AsAction;

    public function handle(GetAllTaskServiceDto $dto): LengthAwarePaginator|Collection
    {
        $tasks = Task::query()
            ->when($dto->board, fn (Builder $builder) => $builder->whereRelation('stage', 'board_id', $dto->board))
            ->when($dto->stage, fn (Builder $builder) => $builder->where('stage_id', $dto->stage))
            ->with($dto->relations);

        return $dto->paginated ? $tasks->paginate(15) : $tasks->get();
    }
}
