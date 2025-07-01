<?php

declare(strict_types=1);

use App\Actions\Boards\DeleteBoardById;
use App\Data\Services\Boards\DeleteBoardByIdServiceDto;
use App\Models\Board;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;

use function Pest\Laravel\assertDatabaseEmpty;

uses(WithFaker::class);

it('delete a board', function () {
    $board = Board::factory()->create();

    $response = DeleteBoardById::make()->handle(
        DeleteBoardByIdServiceDto::from([
            'board_id' => $board->id,
        ])
    );

    expect($response)->toBeTrue();
    assertDatabaseEmpty('boards');
});

it('throws an exception for board not found', function () {
    DeleteBoardById::make()->handle(
        DeleteBoardByIdServiceDto::from([
            'board_id' => $this->faker->randomNumber(),
        ])
    );
})->throws(ModelNotFoundException::class);
