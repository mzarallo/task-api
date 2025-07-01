<?php

declare(strict_types=1);

use App\Actions\Boards\CreateBoard;
use App\Data\Services\Boards\CreateBoardServiceDto;
use App\Models\Board;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;

uses(WithFaker::class);

it('create a board', function () {
    $response = CreateBoard::make()->handle(
        CreateBoardServiceDto::from([
            'name' => $this->faker->sentence,
            'hex_color' => $this->faker->hexColor,
            'author_id' => User::factory()->create()->id,
        ])
    );

    expect($response)->toBeInstanceOf(Board::class);
});
