<?php

declare(strict_types=1);

namespace App\Actions\Tasks;

use App\Data\Services\Tasks\GetTaskByIdServiceDto;
use App\Data\Services\Tasks\UpdateTaskServiceDto;
use App\Models\Task;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateTaskById
{
    use AsAction;

    private int|Task $task;

    public function __construct(private readonly GetTaskById $getTaskById)
    {
    }

    public function handle(int|Task $task, UpdateTaskServiceDto $dto): Task
    {
        $this->setModel($task);

        return $this->update($dto->toArray());
    }

    protected function setModel(int|Task $task): void
    {
        if (is_int($task)) {
            $this->task = $this->getTaskById->handle(
                GetTaskByIdServiceDto::validateAndCreate([
                    'task_id' => $task,
                ])
            );

            return;
        }

        $this->task = $task;
    }

    protected function update(array $attributes)
    {
        return tap($this->task)->update(Arr::snakeKeys($attributes));
    }
}
