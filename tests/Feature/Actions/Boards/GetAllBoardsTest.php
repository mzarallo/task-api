<?php

declare(strict_types=1);

use App\Actions\Boards\GetAllBoards;
use App\Data\Services\Boards\GetAllBoardsServiceDto;
use App\Models\Board;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

it('get boards paginated', function () {
    Board::factory()->count(2)->create();
    Notification::fake();

    $response = GetAllBoards::make()->handle(
        GetAllBoardsServiceDto::from([
            'paginated' => true,
        ])
    );

    expect($response->items())
        ->toHaveCount(2)
        ->and($response)->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($response->items())->toContainOnlyInstancesOf(Board::class);
});

it('get boards as collection', function () {
    Board::factory()->count(2)->create();
    Notification::fake();

    $response = GetAllBoards::make()->handle(
        GetAllBoardsServiceDto::from([
            'paginated' => false,
        ])
    );

    expect($response)->toHaveCount(2)
        ->and($response)->toBeInstanceOf(Collection::class)
        ->and($response)->toContainOnlyInstancesOf(Board::class);
});

it('get boards with relations loaded', function () {
    Board::factory()->create();
    Notification::fake();

    $response = GetAllBoards::make()->handle(
        GetAllBoardsServiceDto::from([
            'relations' => ['stages', 'author'],
            'paginated' => false,
        ])
    );

    expect($response->offsetGet(0)->relationLoaded('author'))->toBeTrue()
        ->and($response->offsetGet(0)->relationLoaded('stages'))->toBeTrue();
});
