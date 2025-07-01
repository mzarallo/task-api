<?php

declare(strict_types=1);

use App\Actions\Tasks\GetTaskById;
use App\Data\Services\Tasks\GetTaskByIdServiceDto;
use App\Models\Task;

it('get task by id', function () {
    $task = Task::factory()->create();

    $response = GetTaskById::make()->handle(
        GetTaskByIdServiceDto::from([
            'task_id' => $task->id,
        ])
    );

    expect($response)->toBeInstanceOf(Task::class)
        ->and($response->id)->toEqual($task->id);
});
it('get task by id with relations loaded', function () {
    $task = Task::factory()->create();

    $response = GetTaskById::make()->handle(
        GetTaskByIdServiceDto::from([
            'task_id' => $task->id,
            'relations' => ['author', 'stage'],
        ])
    );

    expect($response->relationLoaded('author'))->toBeTrue()
        ->and($response->relationLoaded('stage'))->toBeTrue();
});
