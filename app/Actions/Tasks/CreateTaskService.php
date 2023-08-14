<?php

declare(strict_types=1);

namespace App\Actions\Tasks;

use App\Data\Services\Tasks\CreateTaskServiceDto;
use App\Models\Task;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTaskService
{
    use AsAction;

    public function handle(CreateTaskServiceDto $dto): Task
    {
        return Task::query()->create($dto->toArray());
    }
}
