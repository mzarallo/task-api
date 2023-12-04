<?php

declare(strict_types=1);

namespace App\Actions\Tasks;

use App\Data\Services\Tasks\DeleteTaskByIdServiceDto;
use App\Models\Task;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteTaskById
{
    use AsAction;

    public function handle(DeleteTaskByIdServiceDto $dto): ?bool
    {
        return Task::query()
            ->findOrFail($dto->task_id)
            ->delete();
    }
}
