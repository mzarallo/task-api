<?php

declare(strict_types=1);

namespace App\Actions\Tasks;

use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class GetTaskById
{
    use AsAction;

    public function handle(int $taskId, array $whereClause = [], array $relations = []): Task|Model
    {
        return Task::query()
            ->when($whereClause, fn (Builder $query) => $query->where($whereClause))
            ->with($relations)
            ->findOrFail($taskId);
    }
}
