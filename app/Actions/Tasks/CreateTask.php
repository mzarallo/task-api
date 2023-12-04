<?php

declare(strict_types=1);

namespace App\Actions\Tasks;

use App\Data\Services\Tasks\CreateTaskServiceDto;
use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTask
{
    use AsAction;

    public function handle(CreateTaskServiceDto $dto): Task|Model
    {
        return Task::query()->create($dto->toArray());
    }
}
