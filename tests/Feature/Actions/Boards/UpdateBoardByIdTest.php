<?php

declare(strict_types=1);

use App\Actions\Boards\UpdateBoardById;
use App\Data\Services\Boards\UpdateBoardServiceDto;
use App\Models\Board;
use Illuminate\Foundation\Testing\WithFaker;

use function Pest\Laravel\assertDatabaseHas;

uses(WithFaker::class);

it('update board by id', function () {
    $board = Board::factory()->create();
    $params = [
        'name' => $this->faker->name,
        'hex_color' => $this->faker->hexColor,
    ];

    $response = UpdateBoardById::make()->handle(
        $board->id,
        UpdateBoardServiceDto::from($params)
    );

    expect($response)->toBeInstanceOf(Board::class);
    assertDatabaseHas('boards', $params);
});
