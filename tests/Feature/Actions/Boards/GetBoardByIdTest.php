<?php

declare(strict_types=1);

use App\Actions\Boards\GetBoardById;
use App\Data\Services\Boards\GetBoardByIdServiceDto;
use App\Models\Board;

it('get board by id', function () {
    $board = Board::factory()->create();

    $response = GetBoardById::make()->handle(
        GetBoardByIdServiceDto::from([
            'board_id' => $board->id,
        ])
    );

    expect($response)->toBeInstanceOf(Board::class)
        ->and($response->id)->toEqual($board->id);
});

it('get board by id with relations loaded', function () {
    $board = Board::factory()->create();

    $response = GetBoardById::make()->handle(
        GetBoardByIdServiceDto::from([
            'board_id' => $board->id,
            'relations' => ['author', 'stages'],
        ])
    );

    expect($response->relationLoaded('author'))->toBeTrue()
        ->and($response->relationLoaded('stages'))->toBeTrue();
});
