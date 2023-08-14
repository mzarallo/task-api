<?php

declare(strict_types=1);

namespace App\Actions\Tasks;

use App\Data\Services\Tasks\DeleteTaskServiceDto;
use App\Models\Task;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteTaskById
{
    use AsAction;

    public function handle(DeleteTaskServiceDto $dto): bool|null
    {
        return Task::query()
            ->findOrFail($dto->taskId)
            ->delete();
    }
}
