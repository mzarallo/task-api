<?php

declare(strict_types=1);

use App\Actions\Tasks\CreateTask;
use App\Data\Services\Tasks\CreateTaskServiceDto;
use App\Models\Stage;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;

uses(WithFaker::class);

it('create a task', function () {
    $response = CreateTask::make()->handle(
        CreateTaskServiceDto::from([
            'title' => $this->faker->sentence,
            'description' => $this->faker->realText,
            'author_id' => User::factory()->create()->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addWeek()->toDateString(),
            'tags' => ['QA', 'DEV'],
            'order' => 1,
            'stage_id' => Stage::factory()->create()->id,
        ])
    );

    expect($response)->toBeInstanceOf(Task::class);
});
