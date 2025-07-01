<?php

declare(strict_types=1);

use App\Actions\Tasks\GetAllTasks;
use App\Data\Services\Tasks\GetAllTaskServiceDto;
use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

it('get tasks paginated', function () {
    Task::factory()->count(2)->create();

    $response = GetAllTasks::make()->handle(
        GetAllTaskServiceDto::from([
            'relations' => [],
            'paginated' => true,
        ])
    );

    expect($response->items())->toHaveCount(2)
        ->and($response)->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($response->items())->toContainOnlyInstancesOf(Task::class);
});

it('get tasks as collection', function () {
    Task::factory()->count(2)->create();

    $response = GetAllTasks::make()->handle(
        GetAllTaskServiceDto::from([
            'relations' => [],
            'paginated' => false,
        ])
    );

    expect($response)->toHaveCount(2)
        ->and($response)->toBeInstanceOf(Collection::class)
        ->and($response)->toContainOnlyInstancesOf(Task::class);
});

it('get tasks with relations loaded', function () {
    Task::factory()->count(2)->create();

    $response = GetAllTasks::make()->handle(
        GetAllTaskServiceDto::from([
            'relations' => ['author', 'stage'],
            'paginated' => true,
        ])
    );

    expect($response->offsetGet(0)->relationLoaded('author'))->toBeTrue()
        ->and($response->offsetGet(0)->relationLoaded('stage'))->toBeTrue();
});
