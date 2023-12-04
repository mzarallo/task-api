<?php

declare(strict_types=1);

namespace App\Actions\Tasks;

use App\Data\Services\Tasks\GetTaskByIdServiceDto;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\LaravelData\Optional;

class GetTaskById
{
    use AsAction;

    public function handle(GetTaskByIdServiceDto $dto): Task|Model
    {
        return Task::query()
            ->when(! $dto->where_clause instanceof Optional, fn (Builder $query) => $query->where($dto->where_clause))
            ->with($dto->relations instanceof Optional ? [] : $dto->relations)
            ->findOrFail($dto->task_id);
    }
}
