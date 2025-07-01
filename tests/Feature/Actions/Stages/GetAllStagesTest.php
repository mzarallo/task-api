<?php

declare(strict_types=1);

use App\Actions\Stages\GetAllStages;
use App\Data\Services\Stages\GetAllStagesServiceDto;
use App\Models\Board;
use App\Models\Stage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

it('get stages paginated', function () {
    Notification::fake();
    $board = Board::factory()->create();
    Stage::factory()->state(['board_id' => $board->id])->count(2)->create();

    $response = GetAllStages::make()->handle(
        GetAllStagesServiceDto::from([
            'board_id' => $board->id,
            'paginated' => true,
        ])
    );

    expect($response->items())->toHaveCount(2)
        ->and($response)->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($response->items())->toContainOnlyInstancesOf(Stage::class);
});

it('get stages as collection', function () {
    Notification::fake();
    $board = Board::factory()->create();
    Stage::factory()->state(['board_id' => $board->id])->count(2)->create();

    $response = GetAllStages::make()->handle(
        GetAllStagesServiceDto::from([
            'board_id' => $board->id,
            'paginated' => false,
        ])
    );

    expect($response)->toHaveCount(2)
        ->and($response)->toBeInstanceOf(Collection::class)
        ->and($response)->toContainOnlyInstancesOf(Stage::class);
});

it('get stages with relations loaded', function () {
    Notification::fake();
    $board = Board::factory()->create();
    Stage::factory()->state(['board_id' => $board->id])->count(2)->create();

    $response = GetAllStages::make()->handle(
        GetAllStagesServiceDto::from([
            'board_id' => $board->id,
            'relations' => ['author', 'tasks'],
            'paginated' => false,
        ])
    );

    expect($response->offsetGet(0)->relationLoaded('author'))->toBeTrue()
        ->and($response->offsetGet(0)->relationLoaded('tasks'))->toBeTrue();
});
