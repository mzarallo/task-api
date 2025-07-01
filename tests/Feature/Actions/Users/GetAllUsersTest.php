<?php

declare(strict_types=1);

use App\Actions\Tasks\GetAllTasks;
use App\Actions\Users\GetAllUsers;
use App\Data\Services\Tasks\GetAllTaskServiceDto;
use App\Data\Services\Users\GetAllUsersServiceDto;
use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

it('get users paginated', function () {
    User::factory()->count(2)->create();

    $response = GetAllUsers::make()->handle(
        GetAllUsersServiceDto::from([
            'paginated' => true,
        ])
    );

    expect($response->items())->toHaveCount(2)
        ->and($response)->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($response->items())->toContainOnlyInstancesOf(User::class);
});

it('get users as collection', function () {
    User::factory()->count(2)->create();

    $response = GetAllUsers::make()->handle(
        GetAllUsersServiceDto::from([
            'paginated' => false,
        ])
    );

    expect($response)->toHaveCount(2)
        ->and($response)->toBeInstanceOf(Collection::class)
        ->and($response)->toContainOnlyInstancesOf(User::class);
});

it('get tasks with relations loaded', function () {
    Task::factory()->count(2)->create();
    Notification::fake();

    $response = GetAllTasks::make()->handle(
        GetAllTaskServiceDto::from([
            'relations' => ['author', 'stage'],
            'paginated' => true,
        ])
    );

    expect($response->offsetGet(0)->relationLoaded('author'))->toBeTrue()
        ->and($response->offsetGet(0)->relationLoaded('stage'))->toBeTrue();
});
