<?php

declare(strict_types=1);

use App\Actions\Tasks\UpdateTaskById;
use App\Data\Services\Tasks\UpdateTaskServiceDto;
use App\Models\Task;
use Illuminate\Foundation\Testing\WithFaker;

use function Pest\Laravel\assertDatabaseHas;

uses(WithFaker::class);

it('update task by id', function () {
    $task = Task::factory()->create();
    $params = [
        'title' => $this->faker->name,
        'description' => $this->faker->realText,
    ];

    $response = UpdateTaskById::make()->handle(
        $task->id,
        UpdateTaskServiceDto::from($params)
    );

    expect($response)->toBeInstanceOf(Task::class);
    assertDatabaseHas('tasks', $params);
});
