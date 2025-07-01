<?php

declare(strict_types=1);

use App\Actions\Tasks\DeleteTaskById;
use App\Data\Services\Tasks\DeleteTaskByIdServiceDto;
use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;

use function Pest\Laravel\assertDatabaseEmpty;

uses(WithFaker::class);

it('delete a task', function () {
    $task = Task::factory()->create();

    $response = DeleteTaskById::make()->handle(
        DeleteTaskByIdServiceDto::from([
            'task_id' => $task->id,
        ])
    );

    expect($response)->toBeTrue();
    assertDatabaseEmpty('tasks');
});

it('throws an exception for task not found', function () {
    DeleteTaskById::make()->handle(
        DeleteTaskByIdServiceDto::from([
            'task_id' => $this->faker->randomNumber(),
        ])
    );
})->throws(ModelNotFoundException::class);
